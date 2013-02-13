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
