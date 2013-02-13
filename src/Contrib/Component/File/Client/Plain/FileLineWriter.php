<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Client\AbstractFileClient;
use Contrib\Component\File\FileHandler\Plain\LineWriterInterface;

class FileLineWriter extends AbstractFileClient
{
    /**
     * Line writer.
     *
     * @var Contrib\Component\File\FileHandler\Plain\LineWriterInterface
     */
    protected $lineHandler;

    /**
     * Constructor.
     *
     * @param LineWriterInterface $lineHandler LineWriter.
     * @param array               $options     Options.
     */
    public function __construct(LineWriterInterface $lineHandler, array $options = array())
    {
        $this->lineHandler = $lineHandler;
        $this->options     = $options + static::getDefaultOptions();
    }

    // API

    /**
     * Write lines to file.
     *
     * @param array   $lines  Lines data to write.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     */
    public function writeLines(array $lines, $length = null)
    {
        if (!$this->lineHandler->getFile()->isWritable()) {
            return false;
        }

        $bytes = 0;

        foreach ($lines as $line) {
            $bytes += $this->lineHandler->write($line, $length);
        }

        return $bytes;
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
        if (!$this->lineHandler->getFile()->isWritable()) {
            return false;
        }

        return $this->lineHandler->seek($offset, $whence);
    }
}
