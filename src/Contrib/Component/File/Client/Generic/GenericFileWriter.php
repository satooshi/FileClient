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
     * @see \Contrib\Component\File\Client\Plain\FileWriter::initWriter()
     */
    protected function initWriter($format)
    {
        if (!isset($this->lineHandler)) {
            $handle = $this->file->openForWrite();

            if ($handle === false) {
                return false;
            }

            $this->lineHandler = $this->createLineWriter($handle, $format);
        }

        return true;
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
