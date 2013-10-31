<?php

namespace Fusion { class Exception extends \Exception {}}

namespace Fusion\Exceptions {

use Fusion\Asset;
use Fusion\Exception;

class CircularDependency extends Exception {
    public function __construct(array $hierarchy) {
        $message = "Circular dependency detected: \n > " . implode("\n > ", $hierarchy);
        parent::__construct($message);
    }
}

class BadInterpreter extends Exception {
    public function __construct($processor, $name) {
        parent::__construct("Could not execute $processor at $name.");
    }
}

class MixedTypes extends Exception {
    public function __construct($required, $found) {
        parent::__construct("Found an instance of $found in a collection of $required.");
    }
}

class ProcessFailure extends Exception {
    public $status;
    public $error;

    /**
     * @param int $status
     * @param string $error
     */
    public function __construct($status, $error) {
        $this->status = $status;
        $this->error  = $error;
        parent::__construct("Error #$status: $error");
    }
}

class SyntaxError extends Exception {
    public function __construct(Asset $asset, $message, $code = null) {
        $message = sprintf('Error in %s:\n\n%s\n', $asset->path(), $message);
        if($code !== null) {
            $message .= "\n$code\n";
        }
        parent::__construct($message);
    }
}

}
