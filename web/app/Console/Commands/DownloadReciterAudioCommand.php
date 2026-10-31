<?php

namespace App\Console\Commands;

use App\Models\Quran\Reciter\Reciter;
use App\Models\Quran\Reciter\ReciterSura;
use App\Support\Mp3Duration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DownloadReciterAudioCommand extends Command
{
    protected $signature = 'quran:download-reciter 
                            {reciter_id=all : ID of the reciter (1 for Sudais, 2 for Alafasy, or "all")}
                            {--force : Overwrite already downloaded files}';

    protected $description = 'Automatically downloads and registers all 114 Quran Surah audios for reciters';

    private array $cdnMap = [
        1 => [
            'name' => 'Abdul Rahman Al-Sudais',
            'base_url' => 'https://server11.mp3quran.net/sds/%03d.mp3',
        ],
        2 => [
            'name' => 'Mishary Rashid Alafasy',
            'base_url' => 'https://server8.mp3quran.net/afs/%03d.mp3',
        ],
    ];

    public function handle(): int
    {
        ini_set('memory_limit', '1024M');
        $target = $this->argument('reciter_id');
        $force = (bool) $this->option('force');
        $suraMap = config('quran_suras.suras', []);

        if (empty($suraMap)) {
            $this->error('config/quran_suras.php is empty or missing.');
            return 1;
        }

        $reciterIds = ($target === 'all') ? array_keys($this->cdnMap) : [(int) $target];

        foreach ($reciterIds as $reciterId) {
            if (!isset($this->cdnMap[$reciterId])) {
                $this->warn("No CDN mapped for reciter ID {$reciterId}. Skipping.");
                continue;
            }

            $reciter = Reciter::find($reciterId);
            if (!$reciter) {
                $this->warn("Reciter with ID {$reciterId} does not exist in database. Skipping.");
                continue;
            }

            $reciterInfo = $this->cdnMap[$reciterId];
            $this->info("Starting download for: {$reciterInfo['name']} (ID: {$reciterId})");

            $storageDir = storage_path("app/public/quran/{$reciterId}");
            if (!is_dir($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            $bar = $this->output->createProgressBar(count($suraMap));
            $bar->start();

            $successCount = 0;
            $failCount = 0;

            foreach ($suraMap as $surahNumber => $meta) {
                $fileName = "{$surahNumber}.mp3";
                $filePath = "{$storageDir}/{$fileName}";
                $cdnUrl = sprintf($reciterInfo['base_url'], $surahNumber);

                // If already downloaded and not forcing, just register in DB
                if (file_exists($filePath) && filesize($filePath) > 0 && !$force) {
                    $duration = Mp3Duration::fromFile($filePath);
                    $this->saveToDb($reciterId, $surahNumber, $meta, $duration);
                    $successCount++;
                    $bar->advance();
                    continue;
                }

                // Stream download from CDN directly to file
                try {
                    $response = Http::withOptions(['sink' => $filePath])
                        ->timeout(180)
                        ->retry(2, 500)
                        ->get($cdnUrl);

                    if ($response->successful() && file_exists($filePath) && filesize($filePath) > 1000) {
                        $duration = Mp3Duration::fromFile($filePath);
                        $this->saveToDb($reciterId, $surahNumber, $meta, $duration);
                        $successCount++;
                    } else {
                        @unlink($filePath);
                        $failCount++;
                        Log::warning("Failed to download Surah {$surahNumber} for reciter {$reciterId} from {$cdnUrl}");
                    }
                } catch (\Throwable $e) {
                    @unlink($filePath);
                    $failCount++;
                    Log::error("Error downloading Surah {$surahNumber} for reciter {$reciterId}: " . $e->getMessage());
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("Finished {$reciterInfo['name']}: {$successCount} succeeded, {$failCount} failed.");
        }

        return 0;
    }

    private function saveToDb(int $reciterId, int $surahNumber, array $meta, int $duration): void
    {
        ReciterSura::updateOrCreate(
            [
                'reciter_id' => $reciterId,
                'number' => $surahNumber,
            ],
            [
                'name' => $meta['name'],
                'revealed_place' => $meta['revealed_place'],
                'duration' => $duration,
                'path' => "/storage/quran/{$reciterId}/{$surahNumber}.mp3",
            ]
        );
    }
}
