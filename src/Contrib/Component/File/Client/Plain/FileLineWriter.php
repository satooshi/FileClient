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
        parent::__construct($options);

        $this->lineHandler = $lineHandler;
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
     * @return integer 0 on success, -1 on failure
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        $this->lineHandler->seek($offset, $whence);
    }
}
