<?php

namespace Fusion;

use Fusion\Asset;
use Fusion\Exceptions;

class AssetCollection extends \ArrayObject implements IAsset {

    private $assetType = null;

    /**
     * Overridden to ensure pushes are unique
     * @param mixed $index
     * @param mixed $value
     */
    public function offsetSet($index, $value) {
        if($index === null) {
            foreach($this as $i) {
                if($i === $value) {
                    return;
                }
            }
        }
        parent::offsetSet($index, $value);
    }

    /**
     * @param callable|string $callback
     * @return array
     */
    public function map($callback) {
        $this->assetType();
        return array_map(
            is_callable($callback)
                ? $callback
                : function($item) use($callback) {
                    return call_user_func([$item, $callback]);
                },
            $this->getArrayCopy());
    }

    /**
     * @return bool
     */
    public function exists() {
        return !in_array(false, $this->map('exists'));
    }

    /**
     * @return string
     */
    public function raw() {
        return implode("\n", $this->map('raw'));
    }

    /**
     * @return string
     */
    public function filtered() {
        return implode("\n", $this->map('filtered'));
    }

    /**
     * @return string
     */
    public function compressed() {
        return implode('', $this->map('compressed'));
    }

    private function assetType() {
        static $baseTypes = [
            'Fusion\\Asset\\StyleSheet',
            'Fusion\\Asset\\JavaScript',
        ];
        if($this->assetType === null && $this->count()) {
            foreach($baseTypes as $i) {
                if(is_a($this[0], $i)) {
                    $this->assetType = $i;
                    break;
                }
            }
            if($this->assetType) {
                foreach($this as $i) {
                    if(!is_a($i, $this->assetType)) {
                        throw new Exceptions\MixedTypes($this->assetType, get_class($i));
                    }
                }
            }
        }
        return $this->assetType;
    }
}
