<?php

namespace Hx\Fusion\Asset;

use Hx\Fusion\Asset;
use Hx\Fusion\Exceptions;
use Hx\Fusion\Process;

class StyleSheet extends Asset {

    use HasDependencies;

    protected function compress() {
        $args = ['-s', '-f', '-t', '--unix-newlines', '-t', 'compressed', '--scss'];
        try {
            return Process::sass($args, parent::compress());
        } catch(Exceptions\ProcessFailure $e) {
            throw new Exceptions\SyntaxError($this, $e->error);
        }
    }

    public function contentType() {
        return 'text/css';
    }

}
