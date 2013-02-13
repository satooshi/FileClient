<?php
namespace Contrib\Component\File\Client;

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
        parent::__construct($options);

        $this->lineHandler = $iterator;
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
