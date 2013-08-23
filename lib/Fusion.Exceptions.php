<?php

namespace Fusion\Exceptions;

class Base extends \Exception {}

class CircularDependency extends Base {
    public function __construct(array $hierarchy) {
        $message = "Circular dependency detected: \n > " . implode("\n > ", $hierarchy);
        parent::__construct($message);
    }
}

class BadInterpreter extends Base {
    public function __construct($processor, $name) {
        parent::__construct("Could not execute $processor at $name.");
    }
}

class MixedTypes extends Base {
    public function __construct($required, $found) {
        parent::__construct("Found an instance of $found in a collection of $required.");
    }
}
