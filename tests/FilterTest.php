<?php

require_once __DIR__ . '/../lib/Fusion/TestCase.php';

use Hx\Fusion;

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

    function testLess() {
        $file = Fusion::file('style.less', __DIR__ . '/fixtures');
        $this->assertContains('html body', $file->filtered());
        $this->assertContains('html body', $file->compressed());
        $this->assertLessThan(strlen($file->filtered()), strlen($file->compressed()));
        $this->assertNotContains('@charset', $file->filtered());
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
        $this->setExpectedException('Hx\\Fusion\\Exceptions\\BadInterpreter');
        Fusion\Process::$paths['sass'] = 'dfkjhaslkfdjvhclkje';
        $file = Fusion::file('style.scss', __DIR__ . '/fixtures');
        $file->compressed();
    }

    /**
     * @param bool $fallbackToSass
     * @param string $expected
     * @dataProvider dataCleanCss
     */
    public function testCleanCss($fallbackToSass, $expected) {
        if($fallbackToSass) {
            Fusion\Process::$paths['cleancss'] = 'gilquebvalkjsdn';
        }
        $file = Fusion::file('style.css', __DIR__ . '/fixtures');
        $compressed = $file->compressed();
        $this->assertContains($expected, $compressed);
    }
    public function dataCleanCss() {
        return [
            [false, '0 1px'],
            [true, '0px 1px']
        ];
    }

}
