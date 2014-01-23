<?php

namespace Hx;

use Hx\Fusion\AssetCollection;

// Traits
require_once __DIR__ . '/Fusion/Asset/HasDependencies.php';

// Classes
require_once __DIR__ . '/Fusion/Exceptions.php';
require_once __DIR__ . '/Fusion/Process.php';
require_once __DIR__ . '/Fusion/Asset.php';
require_once __DIR__ . '/Fusion/Asset/DependenciesAndSelf.php';
require_once __DIR__ . '/Fusion/Asset/JavaScript.php';
require_once __DIR__ . '/Fusion/Asset/StyleSheet.php';
require_once __DIR__ . '/Fusion/Asset/JavaScript/CoffeeScript.php';
require_once __DIR__ . '/Fusion/Asset/StyleSheet/Sass.php';
require_once __DIR__ . '/Fusion/Asset/StyleSheet/Less.php';
require_once __DIR__ . '/Fusion/AssetCollection.php';

class Fusion {

    private static $types = [
        'css'   => 'StyleSheet',
        'scss'  => 'StyleSheet.Sass',
        'sass'  => 'StyleSheet.Sass',
        'less'  => 'StyleSheet.Less',
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

        $class = 'Hx\\Fusion\\Asset';

        if(preg_match('`\.([^./\\\\]+)$`', $path, $m)) {
            $extension = strtolower($m[1]);
            if(isset(self::$types[$extension])) {
                $class = 'Hx\\Fusion\\Asset\\' . str_replace('.', '\\', self::$types[$extension]);
            }
        }

        return self::$pool[$key] = new $class($path, $baseDir);

    }

    public static function glob($globs, $baseDir) {
        if(!is_array($globs)) {
            $globs = [$globs];
        }

        $collection = new AssetCollection();

        $baseDir = realpath(rtrim($baseDir, DIRECTORY_SEPARATOR));

        if(is_dir($baseDir)) {
            $baseDirLength = strlen($baseDir) + 1;
            foreach($globs as $glob) {
                $glob = $baseDir . DIRECTORY_SEPARATOR . ltrim($glob, '/');
                foreach(glob($glob) as $absolutePath) {
                        if(is_file($absolutePath)) {
                        $path = substr($absolutePath, $baseDirLength);
                        $file = self::file($path, $baseDir);
                        foreach($file->dependencies() as $d) {
                            $collection[] = $d;
                        }
                        $collection[] = $file;
                    }
                }
            }
        }

        return $collection;

    }

    public static function clearPool() {
        self::$pool = [];
    }

}
