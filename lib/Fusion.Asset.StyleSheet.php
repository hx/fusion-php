<?php

namespace Fusion\Asset;

use Fusion\Asset;
use Fusion\Process;

class StyleSheet extends Asset {

    use HasDependencies;

    protected function compress() {
        $args = ['-s', '-f', '-t', '--unix-newlines', '-t', 'compressed', '--scss'];
        return Process::sass($args, parent::compress());
    }

}
