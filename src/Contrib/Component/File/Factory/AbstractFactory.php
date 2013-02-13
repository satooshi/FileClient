<?php
namespace Contrib\Component\File\Factory;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Contrib\Component\File\File;

abstract class AbstractFactory
{
    public function createFile($path, array $options = array())
    {
        if (isset($options['throwException'])) {
            $throwException = (bool)$options['throwException'];

            return new File($path, $throwException);
        }

        return new File($path);
    }

    protected function createSerializer()
    {
        $encoders = array(
            new JsonEncoder(),
            new XmlEncoder(),
        );

        $normalizers = array(
            new Normalizer()
        );

        return new Serializer($normalizers, $encoders);
    }
}
