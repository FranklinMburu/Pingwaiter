<?php

namespace App\Services;

class YouTubeService
{
    public function getEmbedUrl($url)
    {
        if (empty($url)) {
            return '';
        }

        $patterns = [
            '/youtube\.com\/watch\?v=([^\&\?]+)/i',
            '/youtu\.be\/([^\&\?]+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return 'https://www.youtube.com/embed/'.$matches[1];
            }
        }

        return $url;
    }
}
