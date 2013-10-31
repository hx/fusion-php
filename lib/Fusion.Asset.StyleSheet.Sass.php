<?php

namespace Fusion\Asset\StyleSheet;

use Fusion\Asset\StyleSheet;
use Fusion\Exceptions;
use Fusion\Process;

class Sass extends StyleSheet {

    protected function filter() {
        $args = ['-fl', '--unix-newlines', '-t', 'expanded'];
        if($this->extension() === 'scss') {
            $args[] = '--scss';
        }
        $args[] = $this->absolutePath();
        try {
            return Process::sass($args);
        } catch(Exceptions\ProcessFailure $e) {
            throw new Exceptions\SyntaxError($this, $e->error);
        }
    }

}
