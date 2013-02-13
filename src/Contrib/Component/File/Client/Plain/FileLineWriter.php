<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Client\AbstractFileLineClient;
use Contrib\Component\File\FileHandler\Plain\LineWriterInterface;

class FileLineWriter extends AbstractFileLineClient implements LineWriterInterface
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

    // LineWriterInterface

    /**
     * Write lines to file.
     *
     * @param array   $line   Lines data to write.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     * @see \Contrib\Component\File\FileHandler\Plain\LineWriterInterface::write()
     */
    public function write($line, $length = null)
    {
        if (!$this->lineHandler->getFile()->isWritable()) {
            return false;
        }

        if (!is_array($line)) {
            throw new \InvalidArgumentException('line must be a lines data array.');
        }

        $bytes = 0;

        foreach ($line as $str) {
            $bytes += $this->lineHandler->write($str, $length);
        }

        return $bytes;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\SeekableFileInterface::seek()
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->lineHandler->getFile()->isWritable()) {
            return false;
        }

        return $this->lineHandler->seek($offset, $whence);
    }
}
