<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Client\AbstractFileReader;
use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;

/**
 * File reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileReader extends AbstractFileReader
{
    // API

    /**
     * Return file content (file_get_contents() function wrapper).
     *
     * @param boolean $explode Whether to explode by new line.
     * @return string File contents
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function read($explode = false)
    {
        if ($this->file->isReadable()) {
            if ($explode) {
                return file($this->file->getPath());
            }

            return file_get_contents($this->file->getPath());
        }

        return false;
    }

    /**
     * Return file content (fgets() function wrapper).
     *
     * @param integer $length Length to read.
     * @return array File content.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function readLines($length = null)
    {
        if (!$this->initReader()) {
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
     * @see \Contrib\Component\File\Client\AbstractFileReader::createLineReader()
     */
    protected function createLineReader($handle, $format = null, $type = null)
    {
        return new LineReader($handle);
    }
}
