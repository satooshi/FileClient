<?php
namespace Contrib\Component\File\Client\Generic;

use Contrib\Component\File\Client\Plain\FileWriter;
use Contrib\Component\File\Client\AbstractGenericFileWriter;

/**
 * Generic file writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileWriter extends AbstractGenericFileWriter
{
    // internal method

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractGenericFileWriter::open()
     */
    protected function open()
    {
        return $this->file->openForWrite();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractGenericFileClient::createFileClient()
     */
    protected function createFileClient()
    {
        return new FileWriter($this->file->getPath(), $this->options);
    }
}
