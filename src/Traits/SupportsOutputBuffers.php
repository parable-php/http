<?php declare(strict_types=1);

namespace Parable\Http\Traits;

trait SupportsOutputBuffers
{
    protected int $level = 0;

    public function getOutputBuffer(): string
    {
        if (!$this->hasActiveOutputBuffer()) {
            return '';
        }

        $this->level--;

        return ob_get_clean();
    }

    public function getAllOutputBuffers(): string
    {
        $content = [];

        while ($this->hasActiveOutputBuffer()) {
            $content[] = $this->getOutputBuffer();
        }

        $content = array_reverse($content);

        return implode($content);
    }

    public function startOutputBuffer(): void
    {
        ob_start();
        $this->level++;
    }

    public function undoOutputBuffer(): void
    {
        $this->getOutputBuffer();
    }

    public function undoAllOutputBuffers(): void
    {
        $this->getAllOutputBuffers();
    }

    public function hasActiveOutputBuffer(): bool
    {
        return $this->level > 0;
    }
}
