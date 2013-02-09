<?php
namespace Contrib\Component\File\FileHandler\Plain;

/**
 * Iterator for file read.
 */
class Iterator implements \Iterator
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
        $this->reader = new Reader($handle);
    }

    // Iterator interface

    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        $this->reader->seek(0);

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
