<?php

namespace Hx\Fusion\Asset;

use Hx\Fusion\Asset;
use Hx\Fusion\IAsset;
use Hx\Fusion\AssetCollection;

class DependenciesAndSelf implements IAsset, \Iterator, \Countable, \ArrayAccess {

    /**
     * @var Asset
     */
    private $owner;

    private $cursor = 0;

    public function __construct(Asset $owner) {
        $this->owner = $owner;
    }

    /**
     * @return bool
     */
    private function onSelf() {
        return key($this->dependencies()) === null;
    }

    /**
     * @return AssetCollection|array
     */
    public function dependencies() {
        return $this->owner->dependencies();
    }

    public function current() {
        return $this->onSelf()
            ? $this->owner
            : current($this->dependencies());
    }

    public function next() {
        if($this->onSelf()) {
            ++$this->cursor;
        }
        else {
            $dependencies = $this->dependencies();
            next($dependencies);
        }
    }

    public function key() {
        $ret = key($this->dependencies());
        if($ret === null && !$this->cursor) {
            $ret = count($this->dependencies());
        }
        return $ret;
    }

    public function valid() {
        return !$this->cursor;
    }

    public function rewind() {
        $dependencies = $this->dependencies();
        reset($dependencies);
        $this->cursor = 0;
    }

    public function exists() {
        return $this->dependencies()->exists() && $this->owner->exists();
    }

    public function raw() {
        return $this->dependencies()->raw() . "\n" . $this->owner->raw();
    }

    public function filtered() {
        return $this->dependencies()->filtered() . "\n" . $this->owner->filtered();
    }

    public function compressed() {
        return $this->dependencies()->compressed() . $this->owner->compressed();
    }

    public function offsetExists($offset) {
        return $offset >= 0 && $offset < $this->count();
    }

    public function offsetGet($offset) {
        if($this->offsetExists($offset)) {
            return $offset < $this->dependencies()->count()
                ? $this->dependencies()[$offset]
                : $this->owner;
        }
    }

    public function offsetSet($offset, $value) {
        throw new \Exception('This collection is read-only');
    }

    public function offsetUnset($offset) {
        throw new \Exception('This collection is read-only');
    }

    public function count() {
        return count($this->dependencies()) + 1;
    }

    /**
     * @return int
     */
    public function mtime() {
        return max($this->owner->mtime(), $this->dependencies()->mtime());
    }
}
