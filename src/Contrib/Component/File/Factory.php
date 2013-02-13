<?php

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

use Contrib\Component\File\Client\AbstractFileIterator;
use Contrib\Component\File\Client\Generic\GenericFileReader;
use Contrib\Component\File\Client\Generic\GenericFileWriter;
use Contrib\Component\File\Client\Generic\GenericFileLineReader;
use Contrib\Component\File\Client\Generic\GenericFileLineWriter;
use Contrib\Component\File\Client\Plain\FileAppender;
use Contrib\Component\File\Client\Plain\FileLineReader;
use Contrib\Component\File\Client\Plain\FileLineWriter;
use Contrib\Component\File\Client\Plain\FileReader;
use Contrib\Component\File\Client\Plain\FileReaderIterator;
use Contrib\Component\File\Client\Plain\FileReaderLimitIterator;
use Contrib\Component\File\Client\Plain\FileWriter;
use Contrib\Component\File\FileHandler\Generic\GenericLineReader;
use Contrib\Component\File\FileHandler\Generic\GenericLineWriter;
use Contrib\Component\File\FileHandler\Plain\LineReader;
use Contrib\Component\File\FileHandler\Plain\LineWriter;
use Contrib\Component\File\FileHandler\Plain\LineIterator;
use Contrib\Component\File\File;

class Factory
{
    // file

    public function createFile($path, array $options = array())
    {
        if (isset($options['throwException'])) {
            $throwException = (bool)$options['throwException'];

            return new File($path, $throwException);
        }

        return new File($path);
    }

    // AbstractFileHandler
    // line handler

    public function createLineReader($path, array $options = array())
    {
        $file = $this->createFile($path, $options);
        $lineHandler = new LineReader($file, $options);
        $lineHandler->openForRead();

        return $lineHandler;
    }

    public function createLineWriter($path, $append = false, array $options = array())
    {
        $file = $this->createFile($path, $options);
        $lineHandler = new LineWriter($file, $options);

        if ($append) {
            $lineHandler->openForAppend();
        } else {
            $lineHandler->openForWrite();
        }

        return $lineHandler;
    }

    // generic line handler

    public function createGenericLineReader($path, $format, $type = null, array $options = array())
    {
        $lineHandler = $this->createLineReader($path, $options);
        $serializer = $this->createSerializer();

        return new GenericLineReader($lineHandler, $serializer, $format, $type);
    }

    public function createGenericLineWriter($path, $format, array $options = array())
    {
        $lineHandler = $this->createLineWriter($path, false, $options);
        $serializer = $this->createSerializer();

        return new GenericLineWriter($lineHandler, $serializer, $format);
    }

    public function createGenericLineAppender($path, $format, array $options = array())
    {
        $lineHandler = $this->createLineWriter($path, true, $options);
        $serializer = $this->createSerializer();

        return new GenericLineWriter($lineHandler, $serializer, $format);
    }

    // AbstractFileClient
    // file line client

    public function createFileLineReader($path, array $options = array())
    {
        $lineHandler = $this->createLineReader($path, $options);

        return new FileLineReader($lineHandler, $options);
    }

    public function createFileLineWriter($path, array $options = array())
    {
        $lineHandler = $this->createLineWriter($path, false, $options);

        return new FileLineWriter($lineHandler, $options);
    }

    public function createFileLineAppender($path, array $options = array())
    {
        $lineHandler = $this->createLineWriter($path, true, $options);

        return new FileLineWriter($lineHandler, $options);
    }

    // AbstractFileClient
    // BaseFileClient
    // file client

    public function createFileReader($path, array $options = array())
    {
        $file = $this->createFile($path, $options);

        return new FileReader($file, $options);
    }

    public function createFileWriter($path, array $options = array())
    {
        $file = $this->createFile($path, $options);

        return new FileWriter($file, $options);
    }

    public function createFileAppender($path, array $options = array())
    {
        $file = $this->createFile($path, $options);

        return new FileAppender($file, $options);
    }

    // AbstractFileClient
    // generic file line client

    public function createGenericFileLineReader($path, $format, $type = null, array $options = array())
    {
        $lineHandler = $this->createGenericLineReader($path, $format, $type, $options);

        return new GenericFileLineReader($lineHandler, $options);
    }

    public function createGenericFileLineWriter($path, $format, array $options = array())
    {
        $lineHandler = $this->createGenericLineWriter($path, $format, $options);

        return new GenericFileLineWriter($lineHandler, $options);
    }

    public function createGenericFileLineAppender($path, $format, array $options = array())
    {
        $lineHandler = $this->createGenericLineAppender($path, $format, $options);

        return new GenericFileLineWriter($lineHandler, $options);
    }

    // generic file client

    protected function createSerializer()
    {
        $encoders = array(
            new JsonEncoder(),
            new XmlEncoder()
        );
        $normalizers = array(
            new Normalizer()
        );

        return new Serializer($normalizers, $encoders);
    }

    public function createGenericFileReader($path, array $options = array())
    {
        $fileClient = $this->createFileReader($path);
        $serializer = $this->createSerializer();

        return new GenericFileReader($fileClient, $serializer, $options);
    }

    public function createGenericFileWriter($path, array $options = array())
    {
        $fileClient = $this->createFileWriter($path);
        $serializer = $this->createSerializer();

        return new GenericFileWriter($fileClient, $serializer, $options);
    }

    public function createGenericFileAppender($path, array $options = array())
    {
        $fileClient = $this->createFileAppender($path);
        $serializer = $this->createSerializer();

        return new GenericFileWriter($fileClient, $serializer, $options);
    }

    // file reader iterator

    public function createLineIterator($path, array $options = array())
    {
        $lineHandler = $this->createLineReader($path, $options);

        return new LineIterator($lineHandler);
    }

    public function createGenericLineIterator($path, $format, $type = null, array $options = array())
    {
        $lineHandler = $this->createGenericLineReader($path, $format, $type, $options);

        return new LineIterator($lineHandler);
    }

    public function createFileReaderIterator($path, array $options = array())
    {
        $lineIterator = $this->createLineIterator($path, $options);

        return $this->createFileReaderIteratorWith($lineIterator, $options);
    }

    public function createGenericFileReaderIterator($path, $format, $type = null, array $options = array())
    {
        $lineIterator = $this->createGenericLineIterator($path, $format, $type = null, $options);

        return $this->createFileReaderIteratorWith($lineIterator, $options);
    }

    protected function createFileReaderIteratorWith($lineIterator, array $options = array())
    {
        $options = $this->configureFileReaderIteratorOptions($options);

        $skipEmptyCount = $options['skipEmptyCount'];
        $limit          = $options['limit'];
        $offset         = $options['offset'];

        if ($limit > 0 && !$skipEmptyCount) {
            $iterator = new \LimitIterator($lineIterator, $offset, $limit);
        } else {
            $iterator = $lineIterator;
        }

        if ($skipEmptyCount && $limit > 0) {
            return new FileReaderLimitIterator($iterator, $options);
        }

        return new FileReaderIterator($iterator, $options);
    }

    // configuration

    protected function getFileReaderIteratorDefaultOptions(array $options = array())
    {
        $default = AbstractFileIterator::getDefaultOptions();
        $additional = array(
            'skipEmptyCount' => true,
            'limit'          => 0,
            'offset'         => 0,
        );

        return $options + $default + $additional;
    }

    protected function configureFileReaderIteratorOptions(array $options = array())
    {
        $default = $this->getFileReaderIteratorDefaultOptions($options);

        $default['limit']  = $this->filterLimit($default['limit']);
        $default['offset'] = $this->filterOffset($default['offset']);

        return $default;
    }

    // internal method

    /**
     * Filter optional limit.
     *
     * @param integer $limit Count of the limit.
     * @return integer
     */
    protected function filterLimit($limit)
    {
        if (is_numeric($limit)) {
            $limit = (int)$limit;
        }

        if (!is_int($limit)) {
            $limit = 0;
        }

        return $limit;
    }

    /**
     * Filter optional offset.
     *
     * @param integer $offset Offset of the limit.
     * @return integer
     */
    protected function filterOffset($offset)
    {
        if (is_numeric($offset)) {
            $offset = (int)$offset;
        }

        if (!is_int($offset)) {
            $offset = 0;
        }

        return $offset;
    }
}
