<?php
namespace Contrib\Component\File\Client\Generic;

use Contrib\Component\File\Client\Plain\FileReader;
use Contrib\Component\File\Client\AbstractGenericFileReader;
use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;
use Contrib\Component\File\FileHandler\Generic\Reader as GenericLineReader;

/**
 * Generic file reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileReader extends AbstractGenericFileReader
{
    // API

    /**
     * Return file content (file_get_contents() function wrapper).
     *
     * @return array File contents.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function readAs($format, $type = null)
    {
        if (!isset($this->serializer)) {
            throw new \RuntimeException('Serializer is not set.');
        }

        if (!isset($this->fileClient)) {
            $this->fileClient = $this->createFileClient();
        }

        $lines = $this->fileClient->read();

        if (!is_string($lines)) {
            return false;
        }

        if ($type === null) {
            return $this->serializer->decode($lines, $format);
        }

        return $this->serializer->deserialize($lines, $type, $format);
    }

    /**
     * Return file content (fgets() function wrapper).
     *
     * @param integer $length Length to read.
     * @return array File content.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function readLinesAs($format, $type = null, $length = null)
    {
        if (!isset($this->serializer)) {
            throw new \RuntimeException('Serializer is not set.');
        }

        if (!$this->initReader($format, $type)) {
            return false;
        }

        $lines = array();

        while (false !== $line = $this->lineHandler->read($length)) {
            $lines[] = $line;
        }

        return $lines;
    }

    // internal method

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractGenericFileClient::createFileClient()
     */
    protected function createFileClient()
    {
        return new FileReader($this->file->getPath(), $this->options);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractGenericFileReader::createLineReader()
     */
    protected function createLineReader($handle, $format, $type = null)
    {
        $lineReader = new LineReader($handle);

        return new GenericLineReader($lineReader, $this->serializer, $format, $type);
    }
}
