<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\AbstractGenericFileHandler;
use Contrib\Component\File\FileHandler\Plain\LineWriterInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Generic line writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericLineWriter extends AbstractGenericFileHandler implements LineWriterInterface
{
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
}
