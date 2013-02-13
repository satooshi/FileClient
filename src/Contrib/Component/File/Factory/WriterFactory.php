<?php
namespace Contrib\Component\File\Factory;

use Contrib\Component\File\FileHandler\Plain\LineWriter;
use Contrib\Component\File\FileHandler\Generic\GenericLineWriter;
use Contrib\Component\File\Client\Plain\FileLineWriter;
use Contrib\Component\File\Client\Plain\FileWriter;
use Contrib\Component\File\Client\Generic\GenericFileWriter;

class WriterFactory extends AbstractFactory
{
    // line handler

    public function createLineWriter($path, array $options = array())
    {
        $file = $this->createFile($path, $options);
        $lineHandler = new LineWriter($file, $options);
        $lineHandler->openForWrite();

        return $lineHandler;
    }

    // generic line handler

    public function createGenericLineWriter($path, $format, array $options = array())
    {
        $lineHandler = $this->createLineWriter($path, $options);
        $serializer = $this->createSerializer();

        return new GenericLineWriter($lineHandler, $serializer, $format);
    }

    // file line client

    public function createFileLineWriter($path, array $options = array())
    {
        $lineHandler = $this->createLineWriter($path, $options);

        return new FileLineWriter($lineHandler, $options);
    }

    // file client

    public function createFileWriter($path, array $options = array())
    {
        $file = $this->createFile($path, $options);

        return new FileWriter($file, $options);
    }

    // generic file line client

    public function createGenericFileLineWriter($path, $format, array $options = array())
    {
        $lineHandler = $this->createGenericLineWriter($path, $format, $options);

        return new FileLineWriter($lineHandler, $options);
    }

    // generic file client

    public function createGenericFileWriter($path, array $options = array())
    {
        $fileClient = $this->createFileWriter($path, $options);
        $serializer = $this->createSerializer();

        return new GenericFileWriter($fileClient, $serializer, $options);
    }
}
