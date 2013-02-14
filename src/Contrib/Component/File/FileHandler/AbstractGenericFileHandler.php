<?php
namespace Contrib\Component\File\FileHandler;

use Contrib\Component\File\SeekableFileInterface;

abstract class AbstractGenericFileHandler implements SeekableFileInterface
{
    protected $lineHandler;

    /**
     * Serializer object.
     *
     * @var Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    /**
     * File format.
     *
     * @var string
     */
    protected $format;

    // SeekableFileInterface

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
