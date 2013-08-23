<?php

namespace Fusion\Asset;

use Fusion\Asset;
use Fusion\Process;

class JavaScript extends Asset {

    use HasDependencies;

    protected function filter() {
        return rtrim(parent::filter(), " \t\r\n;") . ';';
    }

    protected function compress() {
        return Process::uglifyjs(['-mc'], parent::compress());
    }

}
