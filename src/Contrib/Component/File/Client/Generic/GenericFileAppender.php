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
     * @see \Contrib\Component\File\Client\Plain\FileAppender::initWriter()
     */
    protected function initWriter($format)
    {
        if (!isset($this->lineHandler)) {
            $handle = $this->file->openForAppend();

            if ($handle === false) {
                return false;
            }

            $this->lineHandler = $this->createLineWriter($handle, $format);
        }

        return true;
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
