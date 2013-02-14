<?php
namespace Contrib\Component\File\Factory;

use Contrib\Component\File\FileHandler\Plain\LineReader;
use Contrib\Component\File\FileHandler\Generic\GenericLineReader;
use Contrib\Component\File\Client\Plain\FileLineReader;
use Contrib\Component\File\Client\Plain\FileReader;
use Contrib\Component\File\Client\Generic\GenericFileReader;

class ReaderFactory extends AbstractFactory
{
    // line reader

    public function createLineReader($path, array $options = array())
    {
        $file = $this->createFile($path, $options);
        $lineHandler = new LineReader($file, $options);
        $lineHandler->openForRead();

        return $lineHandler;
    }

    public function createFileLineReader($path, array $options = array())
    {
        $lineHandler = $this->createLineReader($path, $options);

        return new FileLineReader($lineHandler, $options);
    }

    // generic line reader

    public function createGenericLineReader($path, $format, $type = null, array $options = array())
    {
        $lineHandler = $this->createLineReader($path, $options);
        $serializer = $this->createSerializer();

        return new GenericLineReader($lineHandler, $serializer, $format, $type);
    }

    public function createGenericFileLineReader($path, $format, $type = null, array $options = array())
    {
        $lineHandler = $this->createGenericLineReader($path, $format, $type, $options);

        return new FileLineReader($lineHandler, $options);
    }

    // file reader

    public function createFileReader($path, array $options = array())
    {
        $file = $this->createFile($path, $options);

        return new FileReader($file, $options);
    }

    public function createGenericFileReader($path, $format, $type = null, array $options = array())
    {
        $fileClient = $this->createFileReader($path, $options);
        $serializer = $this->createSerializer();

        return new GenericFileReader($fileClient, $serializer, $format, $type);
    }
}
