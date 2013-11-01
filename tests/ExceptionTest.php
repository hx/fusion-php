<?php

require_once __DIR__ . '/../lib/Fusion.TestCase.php';

class ExceptionTest extends Fusion\TestCase {

    public function tearDown() {
        Fusion\Process::$paths = [];
    }

    /**
     * @dataProvider processors
     */
    public function testBadProcessors($bin) {
        Fusion\Process::$paths = [
            $bin   => './asdfghjkl'
        ];
        $this->setExpectedException('Fusion\\Exceptions\\BadInterpreter');
        Fusion::file(__DIR__ . "/fixtures/bad/bad.$bin")->filtered();
    }

    /**
     * @dataProvider processors
     */
    public function testBadSyntax($type) {
        $this->setExpectedException('Fusion\\Exceptions\\SyntaxError');
        Fusion::file(__DIR__ . "/fixtures/bad/bad.$type")->filtered();
    }

    public function processors() {
        return [
            ['sass'],
            ['coffee']
        ];
    }

    public function testCircularDependency() {
        $this->setExpectedException('Fusion\\Exceptions\\CircularDependency');
        Fusion::file(__DIR__ . '/fixtures/circular/a.js')->dependencies();
    }

    public function testMissingDependency() {
        $this->setExpectedException('Fusion\\Exceptions\\MissingDependency');
        Fusion::file(__DIR__ . '/fixtures/bad/missing_dependency.js')->dependenciesAndSelf()->filtered();
    }

}
