<?php

namespace Fusion\Asset\JavaScript;

use Fusion\Asset\JavaScript;
use Fusion\Process;

class CoffeeScript extends JavaScript {

    protected function filter() {
        return Process::coffee(['-cs'], parent::filter());
    }

}
