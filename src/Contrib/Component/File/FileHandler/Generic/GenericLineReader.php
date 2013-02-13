<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\Plain\LineReaderInterface;
use Contrib\Component\File\FileHandler\Plain\LineReader;
use Symfony\Component\Serializer\Serializer;

/**
 * Generic line reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericLineReader implements LineReaderInterface
{
    /**
     * Reader object.
     *
     * @var Contrib\Component\File\FileHandler\Plain\LineReaderInterface
     */
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
     * Deserializing class name.
     *
     * @var string
     */
    protected $type;

    /**
     * Constructor.
     *
     * @param Contrib\Component\File\FileHandler\Plain\LineReaderInterface $reader     Line reader.
     * @param Symfony\Component\Serializer\Serializer                      $serializer Serializer.
     * @param string                                                       $format     File format.
     * @param string                                                       $type       Deserializing class name.
     */
    public function __construct(LineReaderInterface $lineHandler, Serializer $serializer, $format, $type = null)
    {
        $this->lineHandler = $lineHandler;
        $this->serializer  = $serializer;
        $this->format      = $format;
        $this->type        = $type;
    }

    // LineReaderInterface

    /**
     * Return file line (fgets() function wrapper).
     *
     * @param integer $length Length to read.
     * @return array Deserialized data.
     */
    public function read($length = null)
    {
        $line = $this->lineHandler->read($length);

        if ($line === false) {
            return false;
        }

        if ($this->type === null) {
            return $this->serializer->decode($line, $this->format);
        }

        return $this->serializer->deserialize($line, $this->type, $this->format);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\FileHandler\Plain\LineReaderInterface::seek()
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        return $this->lineHandler->seek($offset, $whence);
    }
}
