<?php

// Traits
require_once __DIR__ . '/Fusion.Asset.HasDependencies.php';

// Classes
require_once __DIR__ . '/Fusion.Exceptions.php';
require_once __DIR__ . '/Fusion.Process.php';
require_once __DIR__ . '/Fusion.Asset.php';
require_once __DIR__ . '/Fusion.Asset.Javascript.php';
require_once __DIR__ . '/Fusion.Asset.StyleSheet.php';
require_once __DIR__ . '/Fusion.Asset.Javascript.CoffeeScript.php';
require_once __DIR__ . '/Fusion.Asset.StyleSheet.Sass.php';
require_once __DIR__ . '/Fusion.AssetCollection.php';

class Fusion {

    private static $types = [
        'css'   => 'StyleSheet',
        'scss'  => 'StyleSheet.Sass',
        'sass'  => 'StyleSheet.Sass',
        'js'    => 'JavaScript',
        'coffee'=> 'JavaScript.CoffeeScript'
    ];

    private static $pool = [];

    /**
     * Get an asset for a single file.
     * @param string $path The path of the file to be represented. If no base
     * directory is specified, it should be an absolute path.
     * @param string|null $baseDir Absolute path of the assets' base directory.
     * If omitted, the directory of the given file will be used.
     * @return Fusion\Asset
     */
    public static function file($path, $baseDir = null) {

        $key = serialize(func_get_args());
        if(isset(self::$pool[$key])) {
            return self::$pool[$key];
        }

        $class = 'Fusion\\Asset';

        if(preg_match('`\.([^./\\\\]+)$`', $path, $m)) {
            $extension = strtolower($m[1]);
            if(isset(self::$types[$extension])) {
                $class = 'Fusion\\Asset\\' . str_replace('.', '\\', self::$types[$extension]);
            }
        }

        return self::$pool[$key] = new $class($path, $baseDir);

    }

}
