<?php
namespace Contrib\Component\File\Factory;

use Contrib\Component\File\FileHandler\Plain\LineWriter;
use Contrib\Component\File\FileHandler\Generic\GenericLineWriter;
use Contrib\Component\File\Client\Plain\FileLineWriter;
use Contrib\Component\File\Client\Plain\FileAppender;
use Contrib\Component\File\Client\Generic\GenericFileWriter;
use Contrib\Component\File\Client\Generic\GenericFileLineWriter;

class AppenderFactory extends AbstractFactory
{
    // line handler

    public function createLineAppender($path, array $options = array())
    {
        $file = $this->createFile($path, $options);
        $lineHandler = new LineWriter($file, $options);
        $lineHandler->openForAppend();

        return $lineHandler;
    }

    // generic line handler

    public function createGenericLineAppender($path, $format, array $options = array())
    {
        $lineHandler = $this->createLineAppender($path, $options);
        $serializer = $this->createSerializer();

        return new GenericLineWriter($lineHandler, $serializer, $format);
    }

    // file line client

    public function createFileLineAppender($path, array $options = array())
    {
        $lineHandler = $this->createLineAppender($path, $options);

        return new FileLineWriter($lineHandler, $options);
    }

    // file client

    public function createFileAppender($path, array $options = array())
    {
        $file = $this->createFile($path, $options);

        return new FileAppender($file, $options);
    }

    // generic file line client

    public function createGenericFileLineAppender($path, $format, array $options = array())
    {
        $lineHandler = $this->createGenericLineAppender($path, $format, $options);

        return new GenericFileLineWriter($lineHandler, $options);
    }

    // generic file client

    public function createGenericFileAppender($path, array $options = array())
    {
        $fileClient = $this->createFileAppender($path, $options);
        $serializer = $this->createSerializer();

        return new GenericFileWriter($fileClient, $serializer, $options);
    }
}
