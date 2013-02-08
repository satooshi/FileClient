<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\FileHandler\AbstractFileHandler;

/**
 * Iterator for file read.
 */
class Iterator extends AbstractFileHandler implements \Iterator
{
    /**
     * Current line number.
     *
     * @var integer
     */
    protected $numLine;

    /**
     * Current read line.
     *
     * @var string
     */
    protected $line;

    /**
     * Reader object.
     *
     * @var \Contrib\Component\File\FileHandler\Plain\Reader
     */
    protected $reader;

    /**
     * Constructor.
     *
     * @param resource $handle File handle.
     */
    public function __construct($handle)
    {
        $this->reader = $this->createReader($handle);
    }

    /**
     * Create Reader object.
     *
     * @param resource $handle
     * @return \Contrib\Component\File\FileHandler\Plain\Reader
     */
    protected function createReader($handle)
    {
        return new Reader($handle);
    }

    // Iterator interface

    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        fseek($this->reader->getHandle(), 0);

        $this->line    = $this->reader->read();
        $this->numLine = 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator::valid()
     */
    public function valid()
    {
        return $this->line !== false;
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator::current()
     */
    public function current()
    {
        return $this->line;
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator::key()
     */
    public function key()
    {
        return $this->numLine;
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator::next()
     */
    public function next()
    {
        if ($this->valid()) {
            $this->line = $this->reader->read();

            $this->numLine++;
        }
    }
}
