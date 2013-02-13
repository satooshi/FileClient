<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Client\AbstractFileClient;
use Contrib\Component\File\FileHandler\Plain\LineReaderInterface;

class FileLineReader extends AbstractFileClient
{
    /**
     * Line reader.
     *
     * @var Contrib\Component\File\FileHandler\Plain\LineReaderInterface
     */
    protected $lineHandler;

    /**
     * Constructor.
     *
     * @param LineReaderInterface $lineHandler LineReader.
     * @param array               $options     Options.
     */
    public function __construct(LineReaderInterface $lineHandler, array $options = array())
    {
        $this->lineHandler = $lineHandler;
        $this->options     = $options + static::getDefaultOptions();
    }

    // API

    /**
     * Return file content.
     *
     * @param integer $length Length to read.
     * @return array File content.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function readLines($length = null)
    {
        if (!$this->lineHandler->getFile()->isReadable()) {
            return false;
        }

        $lines = array();

        while (false !== $line = $this->lineHandler->read($length)) {
            $lines[] = $line;
        }

        return $lines;
    }

    /**
     * Seek on a file pointer.
     *
     * @param integer $offset
     * @param string  $whence
     * @return boolean true on success, false on failure.
     * @throws \RuntimeException If file handle is not set.
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->lineHandler->getFile()->isReadable()) {
            return false;
        }

        return $this->lineHandler->seek($offset, $whence);
    }
}
