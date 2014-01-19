<?php

namespace Fusion\Asset\StyleSheet;

use Fusion\Asset\StyleSheet;
use Fusion\Exceptions;
use Fusion\Process;

class Less extends StyleSheet {

    protected function filter() {
        try {
            return preg_replace('`^@charset\b.*?[\r\n]+`', '', Process::lessc(['--line-numbers=comments', '-'], parent::filter()));
        } catch(Exceptions\ProcessFailure $e) {
            throw new Exceptions\SyntaxError($this, $e->error);
        }
    }

}
