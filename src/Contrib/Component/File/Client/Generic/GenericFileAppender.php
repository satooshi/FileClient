<?php
namespace Contrib\Component\File\Client\Generic;

use Contrib\Component\File\Client\Plain\FileAppender;
use Contrib\Component\File\Client\AbstractGenericFileWriter;

/**
 * Generic file appender.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileAppender extends AbstractGenericFileWriter
{
    // internal method

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractGenericFileWriter::open()
     */
    protected function open()
    {
        return $this->file->openForAppend();
    }

    /**
     *
     *
     * @return \Contrib\Component\File\Client\Plain\FileAppender
     */
    protected function createFileClient()
    {
        return new FileAppender($this->file->getPath(), $this->options);
    }
}
