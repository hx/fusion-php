<?php

namespace Fusion\Asset;

use Fusion\Asset;
use Fusion\Exceptions;
use Fusion\Process;

class JavaScript extends Asset {

    use HasDependencies;

    protected function filter() {
        return rtrim(parent::filter(), " \t\r\n;") . ';';
    }

    protected function compress() {
        try {
            return Process::uglifyjs(['-mc'], parent::compress());
        } catch(Exceptions\ProcessFailure $e) {
            throw new Exceptions\SyntaxError($this, $e->error);
        }
    }

}
