<?php

namespace Fusion\Asset\JavaScript;

use Fusion\Asset\JavaScript;
use Fusion\Exceptions;
use Fusion\Process;

class CoffeeScript extends JavaScript {

    protected function filter() {
        try {
            return Process::coffee(['-cs'], parent::filter());
        } catch(Exceptions\ProcessFailure $e) {
            throw new Exceptions\SyntaxError($this, $e->error);
        }
    }

}
