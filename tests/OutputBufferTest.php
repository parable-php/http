<?php declare(strict_types=1);

namespace Parable\Http\Tests;

use Parable\Http\Traits\SupportsOutputBuffers;

class OutputBufferTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SupportsOutputBuffers
     */
    protected $bufferImplementation;

    public function setUp()
    {
        parent::setUp();

        $this->bufferImplementation = new class {
            use SupportsOutputBuffers;
        };
    }

    public function testStartBuffers()
    {
        self::assertFalse($this->bufferImplementation->isActive());

        $this->bufferImplementation->start();

        self::assertTrue($this->bufferImplementation->isActive());
    }

    public function testStartAndGetBuffers()
    {
        $this->bufferImplementation->start();

        echo 'test';

        $content = $this->bufferImplementation->get();

        self::assertSame('test', $content);
    }

    public function testGetBufferWithoutBeingActiveReturnsEmptyString()
    {
        $content = $this->bufferImplementation->get();

        self::assertSame('', $content);
    }

    public function testStartAndGetMultipleBuffers()
    {
        $this->bufferImplementation->start();

        echo 'test';

        $content1 = $this->bufferImplementation->get();

        $this->bufferImplementation->start();

        echo 'again!';

        $content2 = $this->bufferImplementation->get();

        self::assertSame('test', $content1);
        self::assertSame('again!', $content2);
    }

    public function testStartMultipleAndGetAllBuffers()
    {
        $this->bufferImplementation->start();

        echo 'test';

        $this->bufferImplementation->start();

        echo ' again!';

        $content = $this->bufferImplementation->getAll();

        self::assertSame('test again!', $content);
    }

    public function testUndoOneLevel()
    {
        $this->bufferImplementation->start();

        echo 'test';

        $this->bufferImplementation->undo();

        self::assertSame('', $this->bufferImplementation->getAll());
    }

    public function testUndoOneLevelOnMultipleLevelsOfBuffers()
    {
        $this->bufferImplementation->start();

        echo 'test 1 ';

        $this->bufferImplementation->start();

        echo 'test 2 ';

        $this->bufferImplementation->undo();

        $this->bufferImplementation->start();

        echo 'test 3';

        self::assertSame('test 1 test 3', $this->bufferImplementation->getAll());
    }

    public function testUndoAllOnMultipleLevelsOfBuffers()
    {
        $this->bufferImplementation->start();

        echo 'test 1 ';

        $this->bufferImplementation->start();

        echo 'test 2 ';

        $this->bufferImplementation->start();

        echo 'test 3';

        $this->bufferImplementation->undoAll();

        self::assertSame('', $this->bufferImplementation->getAll());
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->bufferImplementation->undoAll();
    }
}
