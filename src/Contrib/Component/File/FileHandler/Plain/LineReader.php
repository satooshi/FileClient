<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\FileHandler\AbstractFileHandler;

/**
 * File line reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class LineReader extends AbstractFileHandler implements LineReaderInterface
{
    // API

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\FileHandler\Plain\LineReaderInterface::read()
     */
    public function read($length = null)
    {
        if ($length === null || !is_int($length)) {
            $line = fgets($this->handle);
        } else {
            $line = fgets($this->handle, $length);
        }

        // convert encoding
        if ($this->options['convert']) {
            return mb_convert_encoding(
                $line,
                $this->options['toEncoding'],
                $this->options['fromEncoding']
            );
        }

        return $line;
    }

    /**
     * Open file for read.
     *
     * @return void
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function openForRead()
    {
        $this->handle = $this->file->openForRead();
    }
}
