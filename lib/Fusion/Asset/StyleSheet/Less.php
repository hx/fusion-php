<?php

namespace Fusion\Asset\StyleSheet;

use Fusion\Asset\StyleSheet;
use Fusion\Exceptions;
use Fusion\Process;

class Less extends StyleSheet {

    protected function filter() {
        try {
            return Process::lessc(['-'], parent::filter());
        } catch(Exceptions\ProcessFailure $e) {
            throw new Exceptions\SyntaxError($this, $e->error);
        }
    }

}
