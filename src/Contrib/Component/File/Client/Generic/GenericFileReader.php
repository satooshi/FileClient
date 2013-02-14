<?php
namespace Contrib\Component\File\Client\Generic;

use Contrib\Component\File\Client\AbstractGenericFileClient;
use Contrib\Component\File\Client\Plain\FileReaderInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Generic file reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileReader extends AbstractGenericFileClient implements FileReaderInterface
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
     * @param Contrib\Component\File\Client\Plain\FileReaderInterface $fileClient FileReader.
     * @param Symfony\Component\Serializer\Serializer                 $serializer Serializer.
     */
    public function __construct(FileReaderInterface $fileClient, Serializer $serializer, $format, $type = null)
    {
        $this->fileClient = $fileClient;
        $this->serializer = $serializer;
        $this->format     = $format;
        $this->type       = $type;
    }

    // FileReaderInterface

    /**
     * Return file content.
     *
     * @param string $format File format.
     * @param string $type   Deserializing class name.
     * @return array File contents.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     * @see \Contrib\Component\File\Client\Plain\FileReaderInterface::read()
     */
    public function read()
    {
        $lines = $this->fileClient->read();

        if (!is_string($lines)) {
            return false;
        }

        if ($this->type === null) {
            return $this->serializer->decode($lines, $this->format);
        }

        return $this->serializer->deserialize($lines, $this->type, $this->format);
    }
}
