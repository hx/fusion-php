<?php

namespace Fusion;

class AssetCollection extends \ArrayObject implements IAsset {

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
     * @return bool
     */
    public function exists() {
        // TODO: Implement exists() method.
    }

    /**
     * @return string
     */
    public function raw() {
        // TODO: Implement raw() method.
    }

    /**
     * @return string
     */
    public function filtered() {
        // TODO: Implement filtered() method.
    }

    /**
     * @return string
     */
    public function compressed() {
        // TODO: Implement compressed() method.
    }
}
