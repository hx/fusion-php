<?php

namespace Fusion\Asset;

use Fusion\Asset;
use Fusion\Process;

class JavaScript extends Asset {

    use HasDependencies;

    protected function compress() {
        return Process::uglifyjs(['-mc'], parent::compress());
    }

}
