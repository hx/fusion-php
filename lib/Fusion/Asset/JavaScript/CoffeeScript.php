<?php

namespace Hx\Fusion\Asset\JavaScript;

use Hx\Fusion\Asset\JavaScript;
use Hx\Fusion\Exceptions;
use Hx\Fusion\Process;

class CoffeeScript extends JavaScript {

    protected function filter() {
        try {
            return Process::coffee(['-cs'], parent::filter());
        } catch(Exceptions\ProcessFailure $e) {
            throw new Exceptions\SyntaxError($this, $e->error);
        }
    }

}
