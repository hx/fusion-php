<?php

namespace Fusion\Asset\StyleSheet;

use Fusion\Asset\StyleSheet;
use Fusion\Process;

class Sass extends StyleSheet {

    protected function filter() {
        $args = ['-fl', '--unix-newlines', '-t', 'expanded'];
        if($this->extension() === 'scss') {
            $args[] = '--scss';
        }
        $args[] = $this->absolutePath();
        return Process::sass($args);
    }

}
