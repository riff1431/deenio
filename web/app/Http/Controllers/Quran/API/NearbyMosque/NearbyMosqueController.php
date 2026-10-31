<?php

namespace App\Http\Controllers\Quran\API\NearbyMosque;

use App\Http\Controllers\Controller;
use App\Repositories\Setting\SettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NearbyMosqueController extends Controller
{
    /**
     * Get nearby mosques using Google Maps with automatic OpenStreetMap fallback
     */
    public function getNearbyMosques(Request $request)
    {
        $lat = floatval($request->get('lat', 23.8103));
        $lng = floatval($request->get('lng', 90.4125));
        $rawRadius = floatval($request->get('radius', 2));

        // Normalize radius to meters (if <= 50, assume kilometers)
        $radiusMeters = $rawRadius <= 50 ? intval($rawRadius * 1000) : intval($rawRadius);
        $radiusKm = round($radiusMeters / 1000, 1);

        // Cache results by lat, lng, radius for 1 hour to prevent repeat API calls
        $cacheKey = "nearby_mosques_" . round($lat, 3) . "_" . round($lng, 3) . "_{$radiusMeters}";

        $results = Cache::remember($cacheKey, 3600, function () use ($lat, $lng, $radiusMeters) {
            // 1. Try Google Places API first if configured and working
            $googleResults = $this->fetchFromGooglePlaces($lat, $lng, $radiusMeters);
            if (!empty($googleResults)) {
                return [
                    'source' => 'google_maps',
                    'places' => $googleResults,
                ];
            }

            // 2. Open-source Fallback: OpenStreetMap Overpass API
            $overpassResults = $this->fetchFromOverpass($lat, $lng, $radiusMeters);
            if (!empty($overpassResults)) {
                return [
                    'source' => 'openstreetmap_overpass',
                    'places' => $overpassResults,
                ];
            }

            // 3. Secondary Open-source Fallback: OpenStreetMap Nominatim
            $nominatimResults = $this->fetchFromNominatim($lat, $lng, $radiusMeters);
            return [
                'source' => 'openstreetmap_nominatim',
                'places' => $nominatimResults,
            ];
        });

        // Sort results by nearest distance
        $places = $results['places'] ?? [];
        usort($places, function ($a, $b) {
            return ($a['distance_meters'] ?? 0) <=> ($b['distance_meters'] ?? 0);
        });

        return response()->json([
            'status' => true,
            'source' => $results['source'] ?? 'openstreetmap',
            'radius_km' => $radiusKm,
            'count' => count($places),
            'results' => $places,
            'data' => $places, // Dual support for results and data keys
        ]);
    }

    /**
     * Attempt to fetch from Google Places API
     */
    protected function fetchFromGooglePlaces($lat, $lng, $radiusMeters): array
    {
        try {
            $apiKey = null;
            $setting = resolve(SettingRepository::class)->findAppSettingWithName('google_map_key', 'app');
            if ($setting && !empty($setting->value)) {
                try {
                    $apiKey = decrypt($setting->value);
                } catch (\Exception $e) {
                    $apiKey = $setting->value;
                }
            }

            if (empty($apiKey) || str_contains($apiKey, 'AIzaSyDaMYGQakH1_WpqJDxD1HNNfFfOy1EwTHc')) {
                // Dummy/unbilled key, skip directly to open-source fallback
                return [];
            }

            $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json";
            $response = Http::timeout(5)->get($url, [
                'location' => "{$lat},{$lng}",
                'radius' => $radiusMeters,
                'type' => 'mosque',
                'key' => $apiKey,
            ]);

            if ($response->successful()) {
                $json = $response->json();
                if (($json['status'] ?? '') === 'OK' && !empty($json['results'])) {
                    $formatted = [];
                    foreach ($json['results'] as $item) {
                        $itemLat = (float)($item['geometry']['location']['lat'] ?? 0);
                        $itemLng = (float)($item['geometry']['location']['lng'] ?? 0);
                        $dist = $this->calculateHaversineDistance($lat, $lng, $itemLat, $itemLng);

                        $formatted[] = [
                            'name' => $item['name'] ?? 'Mosque',
                            'vicinity' => $item['vicinity'] ?? ($item['formatted_address'] ?? 'Nearby'),
                            'lat' => $itemLat,
                            'lng' => $itemLng,
                            'distance_meters' => round($dist),
                            'distance_text' => $this->formatDistanceText($dist),
                            'geometry' => [
                                'location' => [
                                    'lat' => $itemLat,
                                    'lng' => $itemLng,
                                ],
                            ],
                        ];
                    }
                    return $formatted;
                }
            }
        } catch (\Exception $e) {
            Log::warning("Google Places API error: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Fetch from OpenStreetMap Overpass API (High precision for religious places of worship)
     */
    protected function fetchFromOverpass($lat, $lng, $radiusMeters): array
    {
        try {
            $query = "[out:json][timeout:8];" .
                "(node[\"amenity\"=\"place_of_worship\"][\"religion\"=\"muslim\"](around:{$radiusMeters},{$lat},{$lng});" .
                "way[\"amenity\"=\"place_of_worship\"][\"religion\"=\"muslim\"](around:{$radiusMeters},{$lat},{$lng}););" .
                "out center 35;";

            $endpoints = [
                'https://overpass-api.de/api/interpreter',
                'https://overpass.kumi.systems/api/interpreter',
            ];

            foreach ($endpoints as $endpoint) {
                try {
                    $response = Http::withHeaders([
                        'User-Agent' => 'DeenioApp/1.0 (https://deenio.huipper.com; support@deenio.com)',
                    ])->timeout(6)->asForm()->post($endpoint, [
                        'data' => $query,
                    ]);

                    if ($response->successful()) {
                        $json = $response->json();
                        $elements = $json['elements'] ?? [];

                        if (!empty($elements)) {
                            $formatted = [];
                            foreach ($elements as $el) {
                                $tags = $el['tags'] ?? [];
                                $itemLat = (float)($el['lat'] ?? ($el['center']['lat'] ?? 0));
                                $itemLng = (float)($el['lon'] ?? ($el['center']['lon'] ?? 0));

                                if ($itemLat == 0 || $itemLng == 0) continue;

                                $dist = $this->calculateHaversineDistance($lat, $lng, $itemLat, $itemLng);

                                // Get best localized name
                                $name = $tags['name'] ?? ($tags['name:en'] ?? ($tags['name:bn'] ?? 'Jame Masjid'));
                                if (trim($name) === '') {
                                    $name = 'Mosque (মসজিদ)';
                                }

                                // Get readable address/vicinity
                                $vicinityParts = array_filter([
                                    $tags['addr:street'] ?? null,
                                    $tags['addr:suburb'] ?? null,
                                    $tags['addr:city'] ?? null,
                                    $tags['name:en'] ?? null,
                                ]);
                                $vicinity = !empty($vicinityParts) ? implode(', ', $vicinityParts) : 'Nearby Mosque';

                                $formatted[] = [
                                    'name' => $name,
                                    'vicinity' => $vicinity,
                                    'lat' => $itemLat,
                                    'lng' => $itemLng,
                                    'distance_meters' => round($dist),
                                    'distance_text' => $this->formatDistanceText($dist),
                                    'geometry' => [
                                        'location' => [
                                            'lat' => $itemLat,
                                            'lng' => $itemLng,
                                        ],
                                    ],
                                ];
                            }
                            return $formatted;
                        }
                    }
                } catch (\Exception $endpointErr) {
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::warning("Overpass API error: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Secondary fallback: OpenStreetMap Nominatim
     */
    protected function fetchFromNominatim($lat, $lng, $radiusMeters): array
    {
        try {
            $radiusKm = $radiusMeters / 1000.0;
            $dLat = $radiusKm / 111.0;
            $cosLat = max(0.1, cos(deg2rad($lat)));
            $dLng = $radiusKm / (111.0 * $cosLat);

            $left = $lng - $dLng;
            $right = $lng + $dLng;
            $top = $lat + $dLat;
            $bottom = $lat - $dLat;

            $url = "https://nominatim.openstreetmap.org/search";
            $response = Http::withHeaders([
                'User-Agent' => 'DeenioApp/1.0 (https://deenio.huipper.com)',
            ])->timeout(6)->get($url, [
                'q' => 'mosque',
                'format' => 'json',
                'bounded' => 1,
                'viewbox' => "{$left},{$top},{$right},{$bottom}",
                'limit' => 25,
            ]);

            if ($response->successful()) {
                $json = $response->json();
                if (is_array($json) && !empty($json)) {
                    $formatted = [];
                    foreach ($json as $item) {
                        $itemLat = (float)($item['lat'] ?? 0);
                        $itemLng = (float)($item['lon'] ?? 0);
                        if ($itemLat == 0 || $itemLng == 0) continue;

                        $dist = $this->calculateHaversineDistance($lat, $lng, $itemLat, $itemLng);

                        $name = $item['name'] ?? null;
                        if (empty($name) && !empty($item['display_name'])) {
                            $parts = explode(',', $item['display_name']);
                            $name = trim($parts[0]);
                        }
                        if (empty($name)) {
                            $name = 'Mosque';
                        }

                        $formatted[] = [
                            'name' => $name,
                            'vicinity' => $item['display_name'] ?? 'Nearby Mosque',
                            'lat' => $itemLat,
                            'lng' => $itemLng,
                            'distance_meters' => round($dist),
                            'distance_text' => $this->formatDistanceText($dist),
                            'geometry' => [
                                'location' => [
                                    'lat' => $itemLat,
                                    'lng' => $itemLng,
                                ],
                            ],
                        ];
                    }
                    return $formatted;
                }
            }
        } catch (\Exception $e) {
            Log::warning("Nominatim API error: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Calculate distance between two coordinates using Haversine formula (returns meters)
     */
    protected function calculateHaversineDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // in meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    /**
     * Format distance to human-readable string (e.g. 350 m, 1.2 km)
     */
    protected function formatDistanceText(float $meters): string
    {
        if ($meters < 1000) {
            return round($meters) . ' m away';
        }
        return round($meters / 1000, 1) . ' km away';
    }
}
