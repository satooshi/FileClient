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
class GenericFileClient
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
