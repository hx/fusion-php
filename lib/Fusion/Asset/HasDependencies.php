<?php

namespace Hx\Fusion\Asset;

use Hx\Fusion;
use Hx\Fusion\AssetCollection;
use Hx\Fusion\Exceptions;

trait HasDependencies {

    private $dependencies = null;

    /**
     * @param null|array $ancestors Used internally to prevent circular dependency
     * @throws \Fusion\Exceptions\MissingDependency
     * @throws \Fusion\Exceptions\CircularDependency
     * @return AssetCollection
     */
    public function dependencies($ancestors = []) {
        /**
         * @type \Fusion\Asset|self $this
         */

        foreach($ancestors as $d) {
            if($d === $this) {
                throw new Exceptions\CircularDependency(array_map(
                    function(Fusion\Asset $x){
                        return $x->path();
                    },
                    $ancestors
                ));
            }
        }

        static $commentPattern = '`^\s*(/\*[\s\S]*?\*/|(\s*(//|#).*(\s+|$))+)`';
        static $requirePattern = '`^\s*(?:\*|//|#)=\s*(require|require_glob)\s+(.+?)\s*$`';

        if($this->dependencies === null) {
            $this->dependencies = new AssetCollection;
            $paths = [];
            if(preg_match($commentPattern, $this->raw(), $commentsMatch)) {
                $lines = preg_split('`[\r\n]+`', $commentsMatch[1]);
                foreach($lines as $line) {
                    if($line && preg_match($requirePattern, $line, $matches)) {
                        switch($matches[1]) {
                            case 'require':
                                $paths[] = dirname($this->absolutePath()) . DIRECTORY_SEPARATOR . $matches[2];
                                break;
                            case 'require_glob':
                                $paths = array_merge($paths, array_filter(
                                    glob(dirname($this->absolutePath()) . DIRECTORY_SEPARATOR . $matches[2], GLOB_MARK),
                                    function($path) {
                                        return substr($path, -1) !== '/';
                                    }
                                ));
                        }
                    }
                }
            }
            $baseDirLength = strlen($this->baseDir()) + 1;
            $ancestorsAndSelf = array_merge($ancestors, [$this]);
            foreach(array_unique($paths) as $i) {
                if(is_file($i)) {
                    $i = realpath($i);
                    $file = Fusion::file(substr($i, $baseDirLength), $this->baseDir());
                    foreach($file->dependencies($ancestorsAndSelf) as $d) {
                        $this->dependencies[] = $d;
                    }
                    $this->dependencies[] = $file;
                }
                else {
                    throw new Fusion\Exceptions\MissingDependency($this, substr($i, $baseDirLength));
                }
            }
        }

        return $this->dependencies;

    }

}
