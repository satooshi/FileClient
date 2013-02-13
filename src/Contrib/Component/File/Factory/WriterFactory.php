<?php
namespace Contrib\Component\File\Factory;

use Contrib\Component\File\FileHandler\Plain\LineWriter;
use Contrib\Component\File\FileHandler\Generic\GenericLineWriter;
use Contrib\Component\File\Client\Plain\FileLineWriter;
use Contrib\Component\File\Client\Plain\FileWriter;
use Contrib\Component\File\Client\Plain\FileAppender;
use Contrib\Component\File\Client\Generic\GenericFileWriter;
use Contrib\Component\File\Client\Generic\GenericFileLineWriter;

class WriterFactory extends AbstractFactory
{
    // line handler

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

    // file line client

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

    // file client

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

    // generic file line client

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
}
