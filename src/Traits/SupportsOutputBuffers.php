<?php declare(strict_types=1);

namespace Parable\Http\Traits;

trait SupportsOutputBuffers
{
    /**
     * @var int
     */
    protected $level = 0;

    public function get(): string
    {
        if (!$this->isActive()) {
            return '';
        }

        $this->level--;

        return ob_get_clean();
    }

    public function getAll(): string
    {
        $content = [];

        while ($this->isActive()) {
            $content[] = $this->get();
        }

        $content = array_reverse($content);

        return implode($content);
    }

    public function start(): void
    {
        ob_start();
        $this->level++;
    }

    public function undo(): void
    {
        $this->get();
    }

    public function undoAll(): void
    {
        $this->getAll();
    }

    public function isActive(): bool
    {
        return $this->level > 0;
    }
}
