<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\Plain\LineWriterInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Generic line writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericLineWriter implements LineWriterInterface
{
    /**
     * Writer object.
     *
     * @var Contrib\Component\File\FileHandler\Plain\LineWriterInterface
     */
    protected $lineHandler;

    /**
     * Serializer object.
     *
     * @var Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    /**
     * Constructor.
     *
     * @param Contrib\Component\File\FileHandler\Plain\LineWriterInterface $lineHandler LineWriter.
     * @param Symfony\Component\Serializer\Serializer                      $serializer  Serializer.
     * @param string                                                       $format      File format.
     */
    public function __construct(LineWriterInterface $lineHandler, Serializer $serializer, $format)
    {
        $this->lineHandler = $lineHandler;
        $this->serializer  = $serializer;
        $this->format      = $format;
    }

    // LineWriterInterface

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\FileHandler\Plain\LineWriterInterface::write()
     */
    public function write($line, $length = null)
    {
        $serialized = $this->serializer->serialize($line, $this->format);

        return $this->lineHandler->write($serialized, $length);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\FileHandler\Plain\LineWriterInterface::seek()
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        return $this->lineHandler->seek($offset, $whence);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\FileHandler\Plain\LineWriterInterface::getFile()
     */
    public function getFile()
    {
        return $this->lineHandler->getFile();
    }
}
