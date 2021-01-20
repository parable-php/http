<?php declare(strict_types=1);

namespace Parable\Http\Tests;

use Parable\Http\Traits\SupportsOutputBuffers;
use PHPUnit\Framework\TestCase;

class OutputBufferTest extends TestCase
{
    /** @var SupportsOutputBuffers */
    protected $bufferImplementation;

    public function setUp(): void
    {
        parent::setUp();

        $this->bufferImplementation = new class {
            use SupportsOutputBuffers;
        };
    }

    public function testStartBuffers(): void
    {
        self::assertFalse($this->bufferImplementation->hasActiveOutputBuffer());

        $this->bufferImplementation->startOutputBuffer();

        self::assertTrue($this->bufferImplementation->hasActiveOutputBuffer());
    }

    public function testStartAndGetBuffers(): void
    {
        $this->bufferImplementation->startOutputBuffer();

        echo 'test';

        $content = $this->bufferImplementation->getOutputBuffer();

        self::assertSame('test', $content);
    }

    public function testGetBufferWithoutBeingActiveReturnsEmptyString(): void
    {
        $content = $this->bufferImplementation->getOutputBuffer();

        self::assertSame('', $content);
    }

    public function testStartAndGetMultipleBuffers(): void
    {
        $this->bufferImplementation->startOutputBuffer();

        echo 'test';

        $content1 = $this->bufferImplementation->getOutputBuffer();

        $this->bufferImplementation->startOutputBuffer();

        echo 'again!';

        $content2 = $this->bufferImplementation->getOutputBuffer();

        self::assertSame('test', $content1);
        self::assertSame('again!', $content2);
    }

    public function testStartMultipleAndGetAllBuffers(): void
    {
        $this->bufferImplementation->startOutputBuffer();

        echo 'test';

        $this->bufferImplementation->startOutputBuffer();

        echo ' again!';

        $content = $this->bufferImplementation->getAllOutputBuffers();

        self::assertSame('test again!', $content);
    }

    public function testUndoOneLevel(): void
    {
        $this->bufferImplementation->startOutputBuffer();

        echo 'test';

        $this->bufferImplementation->startOutputBuffer();

        echo 'test';

        $this->bufferImplementation->undoOutputBuffer();

        self::assertSame('test', $this->bufferImplementation->getAllOutputBuffers());
    }

    public function testUndoOneLevelOnMultipleLevelsOfBuffers(): void
    {
        $this->bufferImplementation->startOutputBuffer();

        echo 'test 1 ';

        $this->bufferImplementation->startOutputBuffer();

        echo 'test 2 ';

        $this->bufferImplementation->undoOutputBuffer();

        $this->bufferImplementation->startOutputBuffer();

        echo 'test 3';

        self::assertSame('test 1 test 3', $this->bufferImplementation->getAllOutputBuffers());
    }

    public function testUndoAllOnMultipleLevelsOfBuffers(): void
    {
        $this->bufferImplementation->startOutputBuffer();

        echo 'test 1 ';

        $this->bufferImplementation->startOutputBuffer();

        echo 'test 2 ';

        $this->bufferImplementation->startOutputBuffer();

        echo 'test 3';

        $this->bufferImplementation->undoAllOutputBuffers();

        self::assertSame('', $this->bufferImplementation->getAllOutputBuffers());
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->bufferImplementation->undoAllOutputBuffers();
    }
}
