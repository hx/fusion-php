<?php

require_once __DIR__ . '/../lib/Fusion/TestCase.php';

use Hx\Fusion;

class AssetTest extends Fusion\TestCase {

    public function testDependenciesAndSelf() {
        $file = Fusion::file('has_dependencies.coffee', __DIR__ . '/fixtures');
        $dependencyCount = $file->dependencies()->count();
        $all = $file->dependenciesAndSelf();
        $this->assertCount($dependencyCount + 1, $all);
        $this->assertSame($file, $all[$dependencyCount]);
        $this->assertSame($file->dependencies()[1], $all[1]);
        $this->assertContains('have dependencies', $all->compressed());
        $this->assertContains('we got there', $all->raw());
        $this->assertSame('application/javascript', $file->contentType());
        $this->assertSame(filemtime(__DIR__ . '/fixtures/has_dependencies.coffee'), $file->mtime());
    }

}
