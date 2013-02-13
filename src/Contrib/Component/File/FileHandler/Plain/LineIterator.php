<?php
namespace Contrib\Component\File\FileHandler\Plain;

/**
 * Iterator for file read.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class LineIterator implements \Iterator
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
     * @var \Contrib\Component\File\FileHandler\Plain\LineReaderInterface
     */
    protected $lineHandler;

    /**
     * Constructor.
     *
     * @param LineReaderInterface $lineHandler LineReader.
     */
    public function __construct(LineReaderInterface $lineHandler)
    {
        $this->lineHandler = $lineHandler;
    }

    // Iterator interface

    /**
     * {@inheritdoc}
     *
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        $this->lineHandler->seek(0);

        $this->line    = $this->lineHandler->read();
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
            $this->line = $this->lineHandler->read();

            $this->numLine++;
        }
    }

    // accessor

    /**
     * Return LineReader.
     *
     * @return \Contrib\Component\File\FileHandler\Plain\LineReaderInterface
     */
    public function getLineReader()
    {
        return $this->lineHandler;
    }
}
