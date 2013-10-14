<?php

namespace Fusion\Asset\StyleSheet;

use Fusion\Asset\StyleSheet;
use Fusion\Process;

class Sass extends StyleSheet {

    protected function filter() {
        $args = ['-fl', '--unix-newlines', '-t', 'expanded', '-E', 'UTF-8'];
        if($this->extension() === 'scss') {
            $args[] = '--scss';
        }
        $args[] = $this->absolutePath();
        return preg_replace('`^@charset\b.*?[\r\n]+`', '', Process::sass($args));
    }

}
