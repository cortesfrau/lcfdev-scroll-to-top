<?php

declare(strict_types=1);

namespace LcfDev\WpScrollToTop\Assets\Vite;

final class Manifest
{
    /** @var array<string, mixed>|null */
    private ?array $entries = null;

    public function entry(string $name): ?Entry
    {
        $manifest = $this->load();
        if ($manifest === null) {
            return null;
        }

        $data = $manifest[$name] ?? null;
        if (!is_array($data)) {
            return null;
        }

        $file = $data['file'] ?? null;
        if (!is_string($file) || $file === '') {
            return null;
        }

        $cssUrls = [];
        if (isset($data['css']) && is_array($data['css'])) {
            foreach ($data['css'] as $css) {
                if (is_string($css) && $css !== '') {
                    $cssUrls[] = $this->url($css);
                }
            }
        }

        return new Entry(
            jsUrl: $this->url($file),
            cssUrls: $cssUrls,
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    private function load(): ?array
    {
        if ($this->entries !== null) {
            return $this->entries;
        }

        $path = LCFDEV_SCROLL_TO_TOP_PATH . 'dist/.vite/manifest.json';
        if (!is_readable($path)) {
            return null;
        }

        $json = file_get_contents($path);
        if ($json === false) {
            return null;
        }

        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            return null;
        }

        /** @var array<string, mixed> $decoded */
        $this->entries = $decoded;

        return $this->entries;
    }

    private function url(string $relative): string
    {
        return LCFDEV_SCROLL_TO_TOP_URL . 'dist/' . ltrim($relative, '/');
    }
}
