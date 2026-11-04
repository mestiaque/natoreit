<?php
namespace ME\Nit\Support;

class Asset
{
    /**
     * Versioned URL for a package asset under public/vendor/nit.
     */
    public static function url(string $path): string
    {
        $file = __DIR__ . '/../public/vendor/nit/' . $path;

        return asset('vendor/nit/' . $path) . (is_file($file) ? '?v=' . filemtime($file) : '');
    }
}
