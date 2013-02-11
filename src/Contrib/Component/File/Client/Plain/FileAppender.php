<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Client\AbstractFileWriter;

/**
 * File appender.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileAppender extends AbstractFileWriter
{
    // API

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractFileWriter::write()
     */
    public function write($lines)
    {
        if ($this->file->isWritable()) {
            return file_put_contents($this->file->getPath(), $lines, FILE_APPEND | LOCK_EX);
        }

        return false;
    }

    // internal method

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractFileWriter::open()
     */
    protected function open()
    {
        return $this->file->openForAppend();
    }
}
