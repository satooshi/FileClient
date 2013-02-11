<?php
namespace Contrib\Component\File\Client\Generic;

use Contrib\Component\File\Client\Plain\FileReaderIterator;
use Contrib\Component\File\Client\AbstractGenericFileReader;
use Contrib\Component\File\FileHandler\Plain\Iterator as LineIterator;
use Contrib\Component\File\FileHandler\Generic\Iterator as GenericLineIterator;

/**
 * Generic file reader iterator.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileReaderIterator extends AbstractGenericFileReader
{
    // internal method

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractGenericFileClient::createFileClient()
     */
    protected function createFileClient()
    {
        return new FileReaderIterator($this->file->getPath(), $this->options);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractGenericFileReader::createLineReader()
     */
    protected function createLineReader($handle, $format = null, $type = null)
    {
        return new LineIterator($handle);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\FileClient::createIterator()
     */
    protected function createIterator($handle, $format = null, $type = null)
    {
        if (!isset($this->lineHandler)) {
            $this->lineHandler = $this->createLineReader($handle, $format, $type);
        }

        return new GenericLineIterator($this->lineHandler);
    }
}
