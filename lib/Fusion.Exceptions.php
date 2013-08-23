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
    public function __construct($name) {
        parent::__construct("Could not execute $name.");
    }
}
