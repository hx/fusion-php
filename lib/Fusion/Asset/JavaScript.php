<?php

namespace Hx\Fusion\Asset;

use Hx\Fusion\Asset;
use Hx\Fusion\Exceptions;
use Hx\Fusion\Process;

class JavaScript extends Asset {

    use HasDependencies;

    protected function filter() {
        return rtrim(parent::filter(), " \t\r\n;") . ';';
    }

    protected function compress() {
        try {
            return Process::uglifyjs(['-mc', 'warnings=false'], parent::compress());
        } catch(Exceptions\ProcessFailure $e) {
            throw new Exceptions\SyntaxError($this, $e->error);
        }
    }

    public function contentType() {
        return 'application/javascript';
    }

}
