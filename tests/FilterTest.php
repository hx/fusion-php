<?php

require_once __DIR__ . '/../lib/Fusion/TestCase.php';

class FilterTest extends Fusion\TestCase {

    public function setUp() {
        parent::setUp();
        Fusion\Process::$paths = [];
        Fusion::clearPool();
    }

    function testSass() {
        $file = Fusion::file('style.scss', __DIR__ . '/fixtures');
        $this->assertContains('html body', $file->filtered());
        $this->assertContains('html body', $file->compressed());
        $this->assertLessThan(strlen($file->filtered()), strlen($file->compressed()));
    }

    function testCoffee() {
        $file = Fusion::file('simple.coffee', __DIR__ . '/fixtures');
        $this->assertContains('alert(', $file->filtered());
    }

    function testUglify() {
        $file = Fusion::file('bulky.js', __DIR__ . '/fixtures');
        $this->assertContains('foo', $file->filtered());
        $this->assertNotContains('foo', $file->compressed());
    }

    function testNoProcessor() {
        $this->setExpectedException('Fusion\\Exceptions\\BadInterpreter');
        Fusion\Process::$paths['sass'] = 'dfkjhaslkfdjvhclkje';
        $file = Fusion::file('style.scss', __DIR__ . '/fixtures');
        $file->compressed();
    }

}
