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
class GenericFileWriter extends AbstractGenericFileClient implements FileWriterInterface
{
    /**
     * Constructor.
     *
     * @param Contrib\Component\File\Client\Plain\FileWriterInterface $fileClient FileWriter.
     * @param Symfony\Component\Serializer\Serializer                 $serializer Serializer.
     */
    public function __construct(FileWriterInterface $fileClient, Serializer $serializer, $format)
    {
        $this->fileClient = $fileClient;
        $this->serializer = $serializer;
        $this->format     = $format;
    }

    // FileWriterInterface

    /**
     * Write lines to file.
     *
     * @param array $content Data to write.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     * @see \Contrib\Component\File\Client\Plain\FileWriterInterface::write()
     */
    public function write($lines)
    {
        $content = $this->serialize($lines);

        return $this->fileClient->write($content);
    }

    // internal method

    /**
     * Serialize content.
     *
     * @param array  $content Content.
     * @param string $format  Format.
     * @return string Formatted Lines.
     */
    protected function serialize(array $content)
    {
        $lines = array();

        foreach ($content as $line) {
            $lines[] = $this->serializer->serialize($line, $this->format);
        }

        return implode($this->options['newLine'], $lines);
    }
}
