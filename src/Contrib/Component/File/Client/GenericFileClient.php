<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;
use Contrib\Component\File\FileHandler\Plain\Writer as LineWriter;
use Contrib\Component\File\FileHandler\Generic\Reader;
use Contrib\Component\File\FileHandler\Generic\Writer;
use Contrib\Component\File\FileHandler\Generic\Iterator;

/**
 * Generic file client.
 */
class GenericFileClient extends FileClient
{
    protected $serializer;

    /**
     * Constructor.
     *
     * @param string  $path                 File path.
     * @param string  $newLine              New line to be written (Default is PHP_EOL).
     * @param boolean $throwException       Whether to throw exception.
     * @param boolean $autoDetectLineEnding Whether to use auto_detect_line_endings.
     */
    public function __construct($path, $serializer, $newLine = PHP_EOL, $throwException = true, $autoDetectLineEnding = true)
    {
        parent::__construct($path, $newLine, $throwException, $autoDetectLineEnding);

        $this->serializer = $serializer;
    }

    // API

    /**
     * Return file content (file_get_contents() function wrapper).
     *
     * @return array File contents.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function read()
    {
        $content = parent::read();

        if (!is_string($content)) {
            return false;
        }

        $lines = explode($this->newLine, $content);

        return $this->decode($lines);
    }

    /**
     * Write lines to file (fule_put_contents() function wrapper).
     *
     * @param array $content Lines data to write.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function write($content)
    {
        $lines = $this->encode($content);

        if (!is_string($lines)) {
            return false;
        }

        return parent::write($lines);
    }

    /**
     * Append lines to file (file_put_contents() function wrapper).
     *
     * @param string $content Lines to append.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function append($content)
    {
        $lines = $this->encode($content);

        if (!is_string($lines)) {
            return false;
        }

        return parent::append($lines);
    }

    // internal method

    /**
     * Parse lines.
     *
     * @param string $lines Line.
     * @return array Parsed data.
     */
    protected function decode($lines)
    {
        $parsedLines = array();

        foreach ($lines as $line) {
            $parsedLines[] = $this->serializer->deserialize($line);
        }

        return $parsedLines;
    }

    /**
     * Format serializing content.
     *
     * @param array $content   Content.
     * @return string Formatted Lines.
     */
    protected function encode($content)
    {
        if (!is_array($content)) {
            return false;
        }

        $lines = array();

        foreach ($content as $line) {
            $lines[] = $this->serializer->serialize($line);
        }

        return implode($this->newLine, $lines);
    }

    // create line handler

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\FileClient::createReader()
     */
    protected function createReader($handle)
    {
        $lineReader = new LineReader($handle);

        return new Reader($lineReader, $this->serializer);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\FileClient::createWriter()
     */
    protected function createWriter($handle)
    {
        $lineWriter = new LineWriter($handle, $this->newLine);

        return new Writer($lineWriter, $this->serializer);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\FileClient::createIterator()
     */
    protected function createIterator($handle)
    {
        if (!isset($this->lineReader)) {
            $this->lineReader = $this->createReader($handle);
        }

        return new Iterator($this->lineReader);
    }
}
