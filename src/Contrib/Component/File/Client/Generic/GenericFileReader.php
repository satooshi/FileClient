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
        if (!isset($this->fileClient)) {
            $this->fileClient = $this->createFileClient();
        }

        $content = $this->fileClient->read();

        if (!is_string($content)) {
            return false;
        }

        $lines = explode($this->newLine, $content);

        if ($type === null) {
            return $this->decode($lines, $format);
        }

        return $this->deserialize($lines, $type, $format);
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
        $lines = array();

        while (false !== $line = $this->readLineAs($format, $type, $length)) {
            $lines[] = $line;
        }

        return $lines;
    }

    // internal method

    /**
     * Decode lines.
     *
     * @param array $lines Lines.
     * @return array Parsed data.
     */
    protected function decode(array $lines, $format)
    {
        $parsedLines = array();

        foreach ($lines as $line) {
            $parsedLines[] = $this->serializer->decode($line, $format);
        }

        return $parsedLines;
    }

    /**
     * Deserialize lines.
     *
     * @param array $lines Lines.
     * @return array Parsed data.
     */
    protected function deserialize(array $lines, $type, $format)
    {
        $parsedLines = array();

        foreach ($lines as $line) {
            $parsedLines[] = $this->serializer->deserialize($line, $type, $format);
        }

        return $parsedLines;
    }

    /**
     * Return file line (fgets() function wrapper).
     *
     * @param integer $length Length to read.
     * @return string File contents.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    protected function readLineAs($format, $type = null, $length = null)
    {
        if (!$this->initReader($format, $type)) {
            return false;
        }

        return $this->lineHandler->read($length);
    }

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
    protected function createLineReader($handle, $format = null, $type = null)
    {
        $lineReader = new LineReader($handle);

        return new GenericLineReader($lineReader, $this->serializer, $format, $type);
    }

    /**
     * Initialize generic line reader.
     *
     * @param string $format
     * @param string $type
     * @return boolean
     */
    protected function initReader($format = null, $type = null)
    {
        if (!isset($this->lineHandler)) {
            $handle = $this->file->openForRead();

            if ($handle === false) {
                return false;
            }

            $this->lineHandler = $this->createLineReader($handle, $format, $type);
        }

        return true;
    }
}
