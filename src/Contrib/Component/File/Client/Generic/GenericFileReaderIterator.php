<?php
namespace Contrib\Component\File\Client\Generic;

use Contrib\Component\File\Client\Plain\FileReaderIterator;
use Contrib\Component\File\Client\AbstractGenericFileReader;
use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;
use Contrib\Component\File\FileHandler\Plain\Iterator as LineIterator;
use Contrib\Component\File\FileHandler\Generic\Iterator as GenericLineIterator;
use Contrib\Component\File\FileHandler\Generic\Reader as GenericLineReader;

/**
 * Generic file reader iterator.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileReaderIterator extends AbstractGenericFileReader
{
    // API
    //TODO rename to walkAs()

    /**
     * Apply a callback to every line except for empty line.
     *
     * @param callable $callback function ($line, $numLine).
     * @param string   $format File format.
     * @param string   $type   Class name to deserialize.
     * @return \Iterator|false Iterator on success, false on failure.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function walk($callback, $format, $type = null)
    {
        if (!isset($this->serializer)) {
            throw new \RuntimeException('Serializer is not set.');
        }

        if (!isset($this->fileClient)) {
            $this->fileClient = $this->createFileClient();
        }

        if (!$this->initReader($format, $type)) {
            return false;
        }

        $this->fileClient->setLineHandler($this->lineHandler);

        return $this->fileClient->walk($callback);
    }

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
    protected function createLineReader($handle, $format, $type = null)
    {
        $lineReader        = new LineReader($handle);
        $genericLineReader = new GenericLineReader($lineReader, $this->serializer, $format, $type);

        return new GenericLineIterator($genericLineReader);
    }

    // accessor

    /**
     * Return whether the client suspended to read file.
     *
     * @return boolean
     */
    public function isSuspended()
    {
        if (isset($this->fileClient)) {
            return $this->fileClient->isSuspended();
        }

        return null;
    }
}
