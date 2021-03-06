<?php

namespace Hx\Fusion\Asset\StyleSheet;

use Hx\Fusion\Asset\StyleSheet;
use Hx\Fusion\Exceptions;
use Hx\Fusion\Process;

class Sass extends StyleSheet {

    protected function filter() {
        $args = ['-fl', '--unix-newlines', '-t', 'expanded', '-E', 'UTF-8'];
        if($this->extension() === 'scss') {
            $args[] = '--scss';
        }
        $args[] = $this->absolutePath();
        try {
            return preg_replace('`^@charset\b.*?[\r\n]+`', '', Process::sass($args));
        } catch(Exceptions\ProcessFailure $e) {
            throw new Exceptions\SyntaxError($this, $e->error);
        }
    }

}
