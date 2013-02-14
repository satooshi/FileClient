<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\AbstractGenericFileHandler;

use Contrib\Component\File\FileHandler\Plain\LineReaderInterface;
use Contrib\Component\File\FileHandler\Plain\LineReader;
use Symfony\Component\Serializer\Serializer;

/**
 * Generic line reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericLineReader extends AbstractGenericFileHandler implements LineReaderInterface
{
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
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\FileHandler\Plain\LineReaderInterface::read()
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
}
