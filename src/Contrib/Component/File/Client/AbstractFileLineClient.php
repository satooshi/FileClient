<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\File\SeekableFileInterface;

abstract class AbstractFileLineClient extends AbstractFileClient implements SeekableFileInterface
{
    protected $lineHandler;

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\SeekableFileInterface::seek()
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        return $this->lineHandler->seek($offset, $whence);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\SeekableFileInterface::getFile()
     */
    public function getFile()
    {
        return $this->lineHandler->getFile();
    }
}
