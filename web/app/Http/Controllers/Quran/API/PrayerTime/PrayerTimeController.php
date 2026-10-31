<?php

namespace App\Http\Controllers\Quran\API\PrayerTime;

use App\Http\Controllers\Controller;
use App\Models\Quran\Prayer\PrayerTime;
use App\Vendor\PrayerTimes\Method;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Vendor\PrayerTimes\PrayerTimes;

class PrayerTimeController extends Controller
{
    /**
     * Retrieve the columns used for prayer times.
     *
     * @return array
     */
    public function columns()
    {
        return [
            'date',
            'imsak',
            'sunrise',
            'fajr_start',
            'zuhr_start',
            'asr_start',
            'maghrib_start',
            'isha_start',
        ];
    }


    public function getPrayerTime(Request $request)
    {
        try {
            $type = $request->get('type', 'automatic');

            switch ($type) {
                case 'manual':
                    return $this->getManualPrayerTime($request);
                case 'automatic':
                default:
                    return $this->getAutomaticPrayerTime($request);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Prayer time error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'status' => true,
                'message' => 'Default prayer times used',
                'data' => $this->defaultPrayerTimeResponse(),
            ]);
        }
    }

    /**
     * Fetch prayer time data manually based on the date and city.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getManualPrayerTime(Request $request)
    {
        try {
            $date = $request->get('date', date('Y-m-d'));
            $city = $request->get('city', 'Makkah');

            $record = PrayerTime::query()
                ->where('date', $date)
                ->where('city', $city)
                ->select($this->columns())
                ->first();

            if ($record) {
                $data = $record->toArray();
                $data['is_jumma'] = date('l', strtotime($date)) === 'Friday';
                $this->fillDefaultPrayerTimeValues($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Manual prayer time data retrieved successfully.',
                    'data' => $data,
                ]);
            }

            // Fallback to automatic prayer times when manual date/city is not found
            return $this->getAutomaticPrayerTime($request);
        } catch (\Throwable $e) {
            return $this->getAutomaticPrayerTime($request);
        }
    }

    /**
     * Fetch automatic prayer times based on location and calculation methods.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAutomaticPrayerTime(Request $request)
    {
        try {
            $lat = (float) $request->get('lat', 23.795631);
            $lng = (float) $request->get('lng', 90.372931);
            $prayerMethod = (string) $request->get('prayer_method', '1');
            $school = (string) $request->get('school', 'STANDARD');
            $timezone = (string) $request->get('timezone', 'Asia/Dhaka');
            $date = (string) $request->get('date', date('Y-m-d'));

            $prayerTimes = new PrayerTimes($prayerMethod, $school);
            $times = $prayerTimes->getTimesForToday(
                $date,
                $lat,
                $lng,
                $timezone
            );

            $data = [
                'date' => Carbon::parse($date)->format('Y-m-d'),
                'is_jumma' => date('l', strtotime($date)) === 'Friday',
                'imsak' => $times['Imsak'] ?? '--',
                'fajr_start' => $times['Fajr'] ?? '--',
                'sunrise' => $times['Sunrise'] ?? '--',
                'zuhr_start' => $times['Dhuhr'] ?? '--',
                'asr_start' => $times['Asr'] ?? '--',
                'maghrib_start' => $times['Maghrib'] ?? '--',
                'isha_start' => $times['Isha'] ?? '--',
                'sehri' => $times['Sehri'] ?? ($times['Fajr'] ?? '--'),
                'iftar' => $times['Iftar'] ?? ($times['Maghrib'] ?? '--')
            ];

            return response()->json([
                'status' => true,
                'message' => 'Automatic prayer time data retrieved successfully.',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Automatic prayer time error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'status' => true,
                'message' => 'Default prayer time data returned due to computation error.',
                'data' => $this->defaultPrayerTimeResponse(),
            ]);
        }
    }

    /**
     * Provide default prayer time values.
     *
     * @return array
     */
    private function defaultPrayerTimeResponse(): array
    {
        return [
            'date' => date('Y-m-d'),
            'is_jumma' => date('l') === 'Friday',
            'imsak' => '04:30',
            'fajr_start' => '04:45',
            'sunrise' => '06:05',
            'zuhr_start' => '11:45',
            'asr_start' => '15:00',
            'maghrib_start' => '17:25',
            'isha_start' => '18:40',
            'sehri' => '04:45',
            'iftar' => '17:25',
        ];
    }

    /**
     * Fill default values for nullable prayer time fields.
     *
     * @param array $data
     */
    private function fillDefaultPrayerTimeValues(&$data)
    {
        $fields = ['date', 'imsak', 'sunrise', 'fajr_start', 'zuhr_start', 'asr_start', 'maghrib_start', 'isha_start', 'sehri', 'iftar'];
        foreach ($fields as $field) {
            $data[$field] = $data[$field] ?? '0:00';
        }
        return $this;
    }

    public function prayerByLocation(Request $request)
    {
        $request->validate([
            'lat'      => 'required|numeric',
            'lng'      => 'required|numeric',
            'timezone' => 'required|string',
        ]);

        $lat      = $request->lat;
        $lng      = $request->lng;
        $timezone = $request->timezone;
        $date     = now($timezone)->format('Y-m-d');

        // Reverse geocode to get city name
        $city = 'Your Location';
        try {
            $geo  = Http::timeout(5)
                ->withHeaders(['User-Agent' => 'ZabiApp/1.0 (contact@zabi.app)'])
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat'    => $lat,
                    'lon'    => $lng,
                    'format' => 'json',
                ])->json();

            $addr     = $geo['address'] ?? [];
            $cityName = $addr['city'] ?? $addr['town'] ?? $addr['village'] ?? $addr['county'] ?? 'Unknown';
            $country  = $addr['country'] ?? '';
            $city     = $country ? "{$cityName}, {$country}" : $cityName;
        } catch (\Throwable) {}

        // Calculate prayer times (method 2 = ISNA, widely compatible)
        try {
            $pt    = new PrayerTimes(2, PrayerTimes::SCHOOL_STANDARD);
            $times = $pt->getTimesForToday($date, $lat, $lng, $timezone);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => 'Failed to calculate prayer times.'], 500);
        }

        $now        = Carbon::now($timezone);
        $nextMarked = false;
        $prayers    = [];

        foreach ([
            ['Fajr',    'Fajr'],
            ['Sunrise', 'Sunrise'],
            ['Dhuhr',   'Dhuhr'],
            ['Asr',     'Asr'],
            ['Maghrib', 'Maghrib'],
            ['Isha',    'Isha'],
        ] as [$label, $key]) {
            $raw    = $times[$key] ?? null;
            $isNext = false;

            if (!$nextMarked && $raw && $raw !== PrayerTimes::INVALID_TIME) {
                $carbon = Carbon::createFromFormat('H:i', $raw, $timezone);
                if ($now->lt($carbon)) {
                    $isNext     = true;
                    $nextMarked = true;
                }
            }

            $prayers[] = [
                'name'    => $label,
                'time'    => ($raw && $raw !== PrayerTimes::INVALID_TIME)
                    ? Carbon::createFromFormat('H:i', $raw, $timezone)->format('h:i A')
                    : '—',
                'is_next' => $isNext,
            ];
        }

        return response()->json([
            'status'  => true,
            'city'    => $city,
            'prayers' => $prayers,
        ]);
    }

    public function getCities()
    {
        $cities = PrayerTime::select('city')->distinct()->orderBy('city', 'ASC')->pluck('city')->toArray();

        return response()->json([
            'status' => true,
            'message' => 'Data fetched successfully',
            'data' => $cities,
        ]);
    }


}
