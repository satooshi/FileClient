<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;
use Contrib\Component\File\FileHandler\Plain\Writer as LineWriter;
use Contrib\Component\File\FileHandler\Generic\Reader;
use Contrib\Component\File\FileHandler\Generic\Writer;
use Contrib\Component\File\FileHandler\Generic\Iterator;

/**
 * Generic file client.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
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
     * Write lines to file (fule_put_contents() function wrapper).
     *
     * @param array $content Lines data to write.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function writeAs($content, $format)
    {
        $lines = $this->serialize($content, $format);

        if (!is_string($lines)) {
            return false;
        }

        return $this->write($lines);
    }

    /**
     * Append lines to file (file_put_contents() function wrapper).
     *
     * @param string $content Lines to append.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function appendAs($content, $format)
    {
        $lines = $this->serialize($content, $format);

        if (!is_string($lines)) {
            return false;
        }

        return $this->append($lines);
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
     * Write lines to file (fgets() function wrapper).
     *
     * @param array   $lines  Lines data to write.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     */
    public function writeLinesAs(array $lines, $format, $length = null)
    {
        $bytes = 0;

        foreach ($lines as $line) {
            $bytes += $this->writeLineAs($line, $format, $length);
        }

        return $bytes;
    }

    /**
     * Append lines to file (fgets() function wrapper).
     *
     * @param array  $lines  Lines data to append.
     * @param string $length Length to write.
     * @return integer Number of bytes written to the file.
     */
    public function appendLinesAs(array $lines, $format, $length = null)
    {
        $bytes = 0;

        foreach ($lines as $line) {
            $bytes += $this->appendLineAs($line, $format, $length);
        }

        return $bytes;
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

    /**
     * Write line to file (fwrite() function wrapper).
     *
     * @param string $line Line to write.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    protected function writeLineAs($line, $format, $length = null)
    {
        if (!isset($this->lineWriter)) {
            $handle = $this->file->openForWrite();

            if ($handle === false) {
                return false;
            }

            $this->lineWriter = $this->createWriter($handle, $format);
        }

        return $this->lineWriter->write($line, $length);
    }

    /**
     * Append line to file (fwrite() function wrapper).
     *
     * @param string $line Line to append.
     * @param integer $length Appending length.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    protected function appendLineAs($line, $format, $length = null)
    {
        if (!isset($this->lineAppender)) {
            $handle = $this->file->openForAppend();

            if ($handle === false) {
                return false;
            }

            $this->lineAppender = $this->createWriter($handle, $format);
        }

        return $this->lineAppender->write($line, $length);
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

    /**
     * Format serializing content.
     *
     * @param array $content   Content.
     * @return string Formatted Lines.
     */
    protected function serialize($content, $format)
    {
        if (!is_array($content)) {
            return false;
        }

        $lines = array();

        foreach ($content as $line) {
            $lines[] = $this->serializer->serialize($line, $format);
        }

        return implode($this->newLine, $lines);
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

        return new Reader($lineReader, $this->serializer, $format, $type);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\FileClient::createWriter()
     */
    protected function createWriter($handle, $format = null)
    {
        $lineWriter = new LineWriter($handle, $this->newLine);

        return new Writer($lineWriter, $this->serializer, $format);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\FileClient::createIterator()
     */
    protected function createIterator($handle, $format = null, $type = null)
    {
        if (!isset($this->lineReader)) {
            $this->lineReader = $this->createReader($handle, $format, $type);
        }

        return new Iterator($this->lineReader);
    }
}
