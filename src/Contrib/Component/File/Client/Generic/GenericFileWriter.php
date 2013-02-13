<?php
namespace Contrib\Component\File\Client\Generic;

use Contrib\Component\File\Client\AbstractGenericFileClient;
use Contrib\Component\File\Client\Plain\FileWriterInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Generic file writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileWriter extends AbstractGenericFileClient
{
    /**
     * @var Contrib\Component\File\Client\Plain\FileWriterInterface
     */
    protected $fileClient;

    /**
     * Constructor.
     *
     * @param Contrib\Component\File\Client\Plain\FileWriterInterface $fileClient FileWriter.
     * @param Symfony\Component\Serializer\Serializer                 $serializer Serializer.
     * @param array                                                   $options    Options.
     */
    public function __construct(FileWriterInterface $fileClient, Serializer $serializer, array $options = array())
    {
        $this->fileClient = $fileClient;
        $this->serializer = $serializer;
        $this->options    = $options + static::getDefaultOptions();
    }

    // API

    /**
     * Write lines to file.
     *
     * @param array $content Data to write.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function writeAs(array $content, $format)
    {
        $lines = $this->serialize($content, $format);

        return $this->fileClient->write($lines);
    }

    // internal method

    /**
     * Serialize content.
     *
     * @param array  $content Content.
     * @param string $format  Format.
     * @return string Formatted Lines.
     */
    protected function serialize(array $content, $format)
    {
        $lines = array();

        foreach ($content as $line) {
            $lines[] = $this->serializer->serialize($line, $format);
        }

        return implode($this->options['newLine'], $lines);
    }
}
