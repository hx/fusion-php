<?php

require_once __DIR__ . '/../lib/Fusion.TestCase.php';

class FusionTest extends Fusion\TestCase {

    public function testAssetBasics() {
        $file = Fusion::file('simple.coffee', __DIR__ . '/fixtures');

        $this->assertInstanceOf('Fusion\\Asset\\JavaScript\\CoffeeScript', $file);
        $this->assertTrue($file->exists());
        $this->assertEquals('simple.coffee', $file->path());
        $this->assertEquals(__DIR__ . '/fixtures', $file->baseDir());
        $this->assertEquals(realpath(__DIR__ . '/fixtures/simple.coffee'), $file->absolutePath());
        $this->assertEquals('coffee', $file->extension());
        $this->assertEquals(file_get_contents(__DIR__ . '/fixtures/simple.coffee'), $file->raw());
    }

    public function testMissingAsset() {
        $file = Fusion::file('dfjhlksajhdfl');

        $this->assertInstanceOf('Fusion\\Asset', $file);
        $this->assertFalse($file->exists());
        $this->assertNull($file->extension());
        $this->assertNull($file->raw());
    }

    public function testDependencies() {
        $file = Fusion::file('has_dependencies.coffee', __DIR__ . '/fixtures');
        $this->assertCount(4, $file->dependencies());
    }

    public function testCircularDependency() {
        $this->setExpectedException('Fusion\\Exceptions\\CircularDependency');
        Fusion::file(__DIR__ . '/fixtures/circular/a.js')->dependencies();
    }

}
