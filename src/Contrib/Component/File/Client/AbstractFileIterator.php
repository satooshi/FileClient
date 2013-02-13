<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\File\FileHandler\Plain\LineReaderInterface;

abstract class AbstractFileIterator extends AbstractFileClient
{
    /**
     * Line iterator.
     *
     * @var \Iterator
     */
    protected $lineHandler;

    /**
     * Whether the client suspended to read file.
     *
     * @var boolean
     */
    protected $isSuspended = false;

    /**
     * Constructor.
     *
     * @param \Iterator $iterator LineIterator.
     * @param array     $options  Options.
     */
    public function __construct(\Iterator $iterator, array $options = array())
    {
        $this->lineHandler = $iterator;
        $this->options     = $options + static::getDefaultOptions();
    }

    // interanal method

    /**
     * Initialize isSuspended.
     *
     * Called in walk() method.
     *
     * @return void
     */
    protected function initSuspended()
    {
        if ($this->lineHandler instanceof \IteratorIterator) {
            $this->isSuspended = $this->lineHandler->getInnerIterator()->valid();
        } elseif ($this->lineHandler instanceof \Iterator) {
            $this->isSuspended = $this->lineHandler->valid();
        } else {
            $this->isSuspended = false;
        }
    }

    /**
     * Filter iterated line in walk().
     *
     * @param string $line
     * @return mixed
     */
    protected function filterIteratedLine($line)
    {
        return $line;
    }

    // accessor

    /**
     * Return whether the client suspended to read file.
     *
     * @return boolean
     */
    public function isSuspended()
    {
        return $this->isSuspended;
    }

    /**
     * Return inner LineReader.
     *
     * @return \Contrib\Component\File\FileHandler\Plain\LineReaderInterface|NULL
     */
    public function getInnerLineReader()
    {
        if ($this->lineHandler instanceof \IteratorIterator) {
            $lineHandler = $this->lineHandler->getInnerIterator();
        } else {
            $lineHandler = $this->lineHandler;
        }

        if (is_object($lineHandler) && method_exists($lineHandler, 'getLineReader')) {
            $lineReader = $lineHandler->getLineReader();

            if ($lineReader instanceof LineReaderInterface) {
                return $lineReader;
            }
        }

        return null;
    }

    /**
     * Return whether the file is readable.
     *
     * @return boolean true if the file is readable.
     * @throws \RuntimeException Throw if the file is not readable and $throwException is set to true.
     */
    public function isReadable()
    {
        $lineReader = $this->getInnerLineReader();

        if ($lineReader === null) {
            return false;
        }

        return $lineReader->getFile()->isReadable();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractFileClient::getDefaultOptions()
     */
    public static function getDefaultOptions()
    {
        return array(
            'limit' => 0,
        ) + parent::getDefaultOptions();
    }
}
