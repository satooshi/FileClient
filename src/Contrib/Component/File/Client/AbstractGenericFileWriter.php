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
     * @param string $content Lines data to write.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function writeAs($content, $format)
    {
        $lines = $this->serialize($content, $format);

        if (!is_string($lines)) {
            return false;
        }

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
        $bytes = 0;

        foreach ($lines as $line) {
            $bytes += $this->writeLineAs($line, $format, $length);
        }

        return $bytes;
    }

    // internal method

    /**
     * Initialize generic line writer.
     *
     * @param string $format
     */
    abstract protected function initWriter($format);

    /**
     * Write line to file (fwrite() function wrapper).
     *
     * @param string  $line   Line to write.
     * @param string  $format Format.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    protected function writeLineAs($line, $format, $length = null)
    {
        if (!$this->initWriter($format)) {
            return false;
        }

        return $this->lineWriter->write($line, $length);
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
        if (!isset($this->serializer)) {
            throw new \RuntimeException('Serializer is not set.');
        }

        $lineWriter = new LineWriter($handle, $this->options['newLine']);

        return new GenericLineWriter($lineWriter, $this->serializer, $format);
    }

    /**
     * Serialize content.
     *
     * @param string $content Content.
     * @param string $format  Format.
     * @return string Formatted Lines.
     */
    protected function serialize($content, $format)
    {
        if (!isset($this->serializer)) {
            throw new \RuntimeException('Serializer is not set.');
        }

        if (!is_array($content)) {
            return false;
        }

        $lines = array();

        foreach ($content as $line) {
            $lines[] = $this->serializer->serialize($line, $format);
        }

        return implode($this->options['newLine'], $lines);
    }
}
