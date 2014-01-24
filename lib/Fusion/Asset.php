<?php

namespace Hx\Fusion;

use Hx\Fusion\Asset\DependenciesAndSelf;

interface IAsset {
    /**
     * @return bool
     */
    public function exists();

    /**
     * @return string
     */
    public function raw();

    /**
     * @return string
     */
    public function filtered();

    /**
     * @return string
     */
    public function compressed();

    /**
     * @return AssetCollection
     */
    public function dependencies();

    /**
     * @return int
     */
    public function mtime();
}

class Asset implements IAsset {

    private $path;
    private $baseDir;
    private $extension;
    private $raw;
    private $filtered;
    private $compressed;
    private $dependenciesAndSelf;

    /**
     * @param string $path The path of the file to be represented. If no base
     * directory is specified, it should be an absolute path.
     * @param string|null $baseDir Absolute path of the assets' base directory.
     * If omitted, the directory of the given file will be used.
     */
    public function __construct($path, $baseDir = null) {
        if($baseDir === null) {
            $baseDir = dirname($path);
            $path    = basename($path);
        }
        $this->path    = ltrim($path, DIRECTORY_SEPARATOR);
        $this->baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR);
    }

    public function exists() {
        return is_file($this->absolutePath());
    }

    /**
     * Returns the absolute path of the represented file, or false if the
     * file doesn't exist
     * @return string
     */
    public function absolutePath() {
        return realpath($this->baseDir . DIRECTORY_SEPARATOR . $this->path);
    }

    /**
     * Returns the path of the represented file, relative to the base
     * directory
     * @return string
     */
    public function path() {
        return $this->path;
    }

    /**
     * Returns the base directory, or false if it doesn't exist.
     * @return string
     */
    public function baseDir() {
        return realpath($this->baseDir);
    }

    /**
     * Returns the represented file's extension in lowercase, or
     * null if it doesn't have one.
     * @return null|string
     */
    public function extension() {
        if($this->extension === null && preg_match('`\.([^./\\\\]+)$`', $this->path, $m)) {
            $this->extension = strtolower($m[1]);
        }
        return $this->extension;
    }

    /**
     * Returns the raw contents of the represented file, or null if
     * it doesn't exist
     * @return null|string
     */
    public function raw() {
        if($this->raw === null && $this->exists()) {
            $this->raw = file_get_contents($this->absolutePath());
        }
        return $this->raw;
    }

    protected function filter() {
        return $this->raw();
    }

    protected function compress() {
        return $this->filtered();
    }

    /**
     * Override this class to specify asset dependencies.
     * @return AssetCollection
     */
    public function dependencies() {
        return new AssetCollection;
    }

    /**
     * @return string
     */
    public function filtered() {
        if($this->filtered === null) {
            $this->filtered = $this->filter();
        }
        return $this->filtered;
    }

    /**
     * @return string
     */
    public function compressed() {
        if($this->compressed === null) {
            $this->compressed = $this->compress();
        }
        return $this->compressed;
    }

    public function dependenciesAndSelf() {
        if($this->dependenciesAndSelf === null) {
            $this->dependenciesAndSelf = new DependenciesAndSelf($this);
        }
        return $this->dependenciesAndSelf;
    }

    /**
     * @return string
     */
    public function contentType() {
        return 'application/octet-stream';
    }

    /**
     * @return int
     */
    public function mtime() {
        return $this->exists()
            ? filemtime($this->absolutePath())
            : null;
    }
}
