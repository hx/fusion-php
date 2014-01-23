<?php

require_once __DIR__ . '/../lib/Fusion/TestCase.php';

use Hx\Fusion;

class AssetCollectionTest extends Fusion\TestCase {

    public function testGlob() {
        $glob = Fusion::glob(['*.coffee', 'b/*.coffee'], __DIR__ . '/fixtures');
        $this->assertCount(6, $glob);
    }

    public function testJoin() {
        $glob = Fusion::glob('c/*', __DIR__ . '/fixtures');
        $this->assertContains('hi', $glob->compressed());
        $this->assertContains('hello', $glob->compressed());
    }

    public function testTypeMismatch() {
        $this->setExpectedException('Hx\\Fusion\\Exceptions\\MixedTypes');
        $glob = Fusion::glob('*', __DIR__ . '/fixtures');
        $glob->raw();
    }

}