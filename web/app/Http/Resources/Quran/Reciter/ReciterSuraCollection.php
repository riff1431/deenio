<?php

namespace App\Http\Resources\Quran\Reciter;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReciterSuraCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reciter_id' => $this->reciter_id,
            'reciter_name' => $this->reciter->name,
            'reciter_avatar' => $this->reciter->profile_picture ? asset($this->reciter->profile_picture) : asset('assets/img/avatar.png'),
            'sura_name' => $this->name,
            'number' => $this->number,
            'duration' => $this->duration * 1000,  // Convert duration to milliseconds
            'revealed_place' => $this->revealed_place,
            'path' => $this->resolveAudioPath(),
        ];
    }

    protected function resolveAudioPath(): string
    {
        // 1. If path is already an external URL, return it directly
        if (!empty($this->path) && preg_match('#^https?://#i', $this->path)) {
            return $this->path;
        }

        // 2. Check if local audio file physically exists on the server
        if (!empty($this->path)) {
            $localPublicPath = public_path($this->path);
            $cleanRelative = preg_replace('#^/?storage/#', '', $this->path);
            $localStoragePath = storage_path('app/public/' . $cleanRelative);

            // Accessible via public_path directly or via valid storage symlink
            if (file_exists($localPublicPath) && is_file($localPublicPath) && filesize($localPublicPath) > 1000) {
                return asset($this->path);
            }

            if (file_exists($localStoragePath) && is_file($localStoragePath) && filesize($localStoragePath) > 1000 && is_link(public_path('storage'))) {
                return asset($this->path);
            }
        }

        // 3. Fallback to Cloudflare CDN (instant streaming, 0 bandwidth load on server)
        $surahPadded = sprintf('%03d', (int) $this->number);
        return match ((int) $this->reciter_id) {
            2 => "https://cdn.mp3quran.net/audio/mishary-alafasy/r1/{$surahPadded}.mp3",
            default => "https://cdn.mp3quran.net/audio/abdulrahman-sudais/r1/{$surahPadded}.mp3",
        };
    }
}
