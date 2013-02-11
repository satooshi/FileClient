<?php
namespace Contrib\Component\File\Client\Generic;

use Contrib\Component\File\Client\Plain\FileReader;

use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;
use Contrib\Component\File\FileHandler\Generic\Reader as GenericLineReader;

class GenericFileReader extends FileReader
{
    /**
     * Return file content (file_get_contents() function wrapper).
     *
     * @return array File contents.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function readAs($format, $type = null)
    {
        $content = $this->read();

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


    /**
     * Return file line (fgets() function wrapper).
     *
     * @param integer $length Length to read.
     * @return string File contents.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    protected function readLineAs($format, $type = null, $length = null)
    {
        if (!isset($this->lineReader)) {
            $handle = $this->file->openForRead();

            if ($handle === false) {
                return false;
            }

            $this->lineReader = $this->createReader($handle, $format, $type);
        }

        return $this->lineReader->read($length);
    }

    // internal method

    /**
     * Parse lines.
     *
     * @param string $lines Line.
     * @return array Parsed data.
     */
    protected function decode($lines, $format)
    {
        $parsedLines = array();

        foreach ($lines as $line) {
            $parsedLines[] = $this->serializer->decode($line, $format);
        }

        return $parsedLines;
    }

    /**
     * Parse lines.
     *
     * @param string $lines Line.
     * @return array Parsed data.
     */
    protected function deserialize($lines, $type, $format)
    {
        $parsedLines = array();

        foreach ($lines as $line) {
            $parsedLines[] = $this->serializer->deserialize($line, $type, $format);
        }

        return $parsedLines;
    }

    // create line handler

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\FileClient::createReader()
     */
    protected function createReader($handle, $format = null, $type = null)
    {
        $lineReader = new LineReader($handle);

        return new GenericLineReader($lineReader, $this->serializer, $format, $type);
    }
}
