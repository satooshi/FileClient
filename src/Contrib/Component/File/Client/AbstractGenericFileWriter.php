<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\File\FileHandler\Plain\Writer as LineWriter;
use Contrib\Component\File\FileHandler\Generic\Writer as GenericLineWriter;

/**
 * Abstract generic file writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractGenericFileWriter extends AbstractGenericFileClient
{
    // API

    /**
     * Write lines to file (fule_put_contents() function wrapper).
     *
     * @param array $content Data to write.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function writeAs(array $content, $format)
    {
        if (!isset($this->serializer)) {
            throw new \RuntimeException('Serializer is not set.');
        }

        $lines = $this->serialize($content, $format);

        if (!isset($this->fileClient)) {
            $this->fileClient = $this->createFileClient();
        }

        return $this->fileClient->write($lines);
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
        if (!isset($this->serializer)) {
            throw new \RuntimeException('Serializer is not set.');
        }

        if (!$this->initWriter($format)) {
            return false;
        }

        $bytes = 0;

        foreach ($lines as $line) {
            $bytes += $this->lineHandler->write($line, $length);
        }

        return $bytes;
    }

    // internal method

    /**
     * Open file for write..
     *
     * @return resource File handle.
     */
    abstract protected function open();

    /**
     * Initialize generic line writer.
     *
     * @param string $format
     */
    protected function initWriter($format)
    {
        if (!isset($this->lineHandler)) {
            $handle = $this->open();

            if ($handle === false) {
                return false;
            }

            $this->lineHandler = $this->createLineWriter($handle, $format);
        }

        return true;
    }

    /**
     * Create generic line writer.
     *
     * @param resource $handle
     * @param string   $format
     * @return \Contrib\Component\File\FileHandler\Generic\Writer
     * @throws \RuntimeException
     */
    protected function createLineWriter($handle, $format = null)
    {
        $lineWriter = new LineWriter($handle, $this->options['newLine']);

        return new GenericLineWriter($lineWriter, $this->serializer, $format);
    }

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
