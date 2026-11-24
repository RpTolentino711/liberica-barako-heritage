<?php

namespace App\Support;

class EncyclopediaSectionParser
{
    public static function parse(string $body): array
    {
        $chunks = preg_split('/(?=^## .+$)/m', trim($body), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $sections = [];

        foreach ($chunks as $chunk) {
            $chunk = trim($chunk);
            $heading = null;
            if (preg_match('/^## ([^\r\n]+)\s*(?:\r?\n|$)/', $chunk, $headingMatch)) {
                $heading = trim($headingMatch[1]);
                $chunk = preg_replace('/^## [^\r\n]+\s*(?:\r?\n|$)/', '', $chunk, 1) ?? $chunk;
            }

            $source = [];
            if (preg_match('/^@{2,}ENCYCLOPEDIA_SOURCE:([A-Za-z0-9+\/=]+)@{2,}\s*(?:\r?\n|$)/m', $chunk, $sourceMatch)) {
                $decoded = base64_decode($sourceMatch[1], true);
                $source = is_string($decoded) ? json_decode($decoded, true) : [];
                $source = is_array($source) ? $source : [];
                $chunk = preg_replace('/^@{2,}ENCYCLOPEDIA_SOURCE:[A-Za-z0-9+\/=]+@{2,}\s*(?:\r?\n|$)/m', '', $chunk, 1) ?? $chunk;
            }

            $urlValue = $source['url'] ?? '';
            $url = is_string($urlValue) ? trim($urlValue) : '';
            $safeUrl = filter_var($url, FILTER_VALIDATE_URL) && in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true)
                ? $url
                : null;
            $labelValue = $source['label'] ?? '';
            $label = is_string($labelValue) ? trim($labelValue) : '';
            if ($label === '' && $safeUrl) $label = (string) parse_url($safeUrl, PHP_URL_HOST);

            $sections[] = [
                'heading' => $heading,
                'paragraphs' => preg_split('/\R\s*\R/', trim($chunk), -1, PREG_SPLIT_NO_EMPTY) ?: [],
                'source_label' => $label,
                'source_url' => $safeUrl,
            ];
        }

        return $sections;
    }
}
