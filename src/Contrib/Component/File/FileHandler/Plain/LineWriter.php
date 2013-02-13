<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\File;
use Contrib\Component\File\FileHandler\AbstractFileHandler;

/**
 * File line writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class LineWriter extends AbstractFileHandler implements LineWriterInterface
{
    // API

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\FileHandler\Plain\LineWriterInterface::write()
     */
    public function write($line, $length = null)
    {
        // convert encoding
        if ($this->options['convert']) {
            $line = mb_convert_encoding(
                $line,
                $this->options['toEncoding'],
                $this->options['fromEncoding']
            );
        }

        if ($length === null || !is_int($length)) {
            return fwrite($this->handle, $this->newLine($line));
        }

        return fwrite($this->handle, $this->newLine($line), $length);
    }

    /**
     * Open file for write.
     *
     * @return void
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function openForWrite()
    {
        $this->handle = $this->file->openForWrite();
    }

    /**
     * Open file for append
     *
     * @return void
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function openForAppend()
    {
        $this->handle = $this->file->openForAppend();
    }

    // internal method

    /**
     * Return string appended new line.
     *
     * @param string $str
     * @return string
     */
    protected function newLine($str)
    {
        return $str . $this->options['newLine'];
    }
}
