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
class GenericFileReader extends AbstractGenericFileClient
{
    /**
     * @var Contrib\Component\File\Client\Plain\FileReaderInterface
     */
    protected $fileClient;

    /**
     * Constructor.
     *
     * @param Contrib\Component\File\Client\Plain\FileReaderInterface $fileClient FileReader.
     * @param Symfony\Component\Serializer\Serializer                 $serializer Serializer.
     * @param array                                                   $options    Options.
     */
    public function __construct(FileReaderInterface $fileClient, Serializer $serializer, array $options = array())
    {
        $this->fileClient = $fileClient;
        $this->serializer = $serializer;
        $this->options    = $options + static::getDefaultOptions();
    }

    // API

    /**
     * Return file content.
     *
     * @param string $format File format.
     * @param string $type   Deserializing class name.
     * @return array File contents.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function readAs($format, $type = null)
    {
        $lines = $this->fileClient->read();

        if (!is_string($lines)) {
            return false;
        }

        if ($type === null) {
            return $this->serializer->decode($lines, $format);
        }

        return $this->serializer->deserialize($lines, $type, $format);
    }
}
