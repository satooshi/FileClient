<?php
namespace Contrib\Component\File\Client\Generic;

use Contrib\Component\Serializer\Factory;
use Symfony\Component\Serializer\Serializer;

/**
 * Generic file writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileWriterTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $content;
    protected $path = './hello.txt';
    protected $unwritablePath = './unwritable';

    protected $throwException = true;
    protected $notThrowException = false;

    protected function setUp()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        $this->content = array(
            new SerializableEntity(array('id' => 1, 'name' => 'hoge')),
        );
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }
        if (is_file($this->unwritablePath)) {
            unlink($this->unwritablePath);
        }
    }


    protected function createObject($path, $throwException = true)
    {
        $serializer = Factory::createSerializer();
        $object = $this->createObjectWithoutSerializer($path, $throwException);
        $object->setSerializer($serializer);

        return $object;
    }

    protected function createObjectWithoutSerializer($path, $throwException = true)
    {
        return new GenericFileWriter($path, array('throwException' => $throwException));
    }

    protected function touchUnwritableFile()
    {
        if (is_file($this->unwritablePath)) {
            unlink($this->unwritablePath);
        }

        touch($this->unwritablePath);
        chmod($this->unwritablePath, 0577);
    }

    // writeAs()

    /**
     * @test
     */
    public function writeAsJson()
    {
        $this->object = $this->createObject($this->path);
        $this->object->writeAs($this->content, 'json');

        $expected = '{"id":1,"name":"hoge"}';
        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function overwriteAsJson()
    {
        $this->object = $this->createObject($this->path);
        $this->object->writeAs($this->content, 'json');
        $this->object->writeAs($this->content, 'json');

        $expected = '{"id":1,"name":"hoge"}';
        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function overwriteAsJsonToExistingFile()
    {
        $this->object = $this->createObject($this->path);
        $this->object->writeAs($this->content, 'json');

        $this->object = $this->createObject($this->path);
        $this->object->writeAs($this->content, 'json');

        $expected = '{"id":1,"name":"hoge"}';
        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotWriteAsJsonIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath, false);
        $actual = $this->object->writeAs($this->content, 'json');

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteAsJsonIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath);
        $this->object->writeAs($this->content, 'json');
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteAsJsonIfSerializerNotSet()
    {
        $this->object = $this->createObjectWithoutSerializer($this->path);
        $this->object->writeAs($this->content, 'json');
    }

    // writeLinesAs()

    /**
     * @test
     */
    public function writeLinesAsJson()
    {
        $expected = '{"id":1,"name":"hoge"}' . PHP_EOL;

        $this->object = $this->createObject($this->path);
        $this->object->writeLinesAs($this->content, 'json');

        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function appendLinesAsJson()
    {
        $this->object = $this->createObject($this->path);
        $this->object->writeLinesAs($this->content, 'json');
        $this->object->writeLinesAs($this->content, 'json');

        $data = '{"id":1,"name":"hoge"}' . PHP_EOL;
        $expected = $data . $data;
        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function overwriteLinesAsJson()
    {
        $this->object = $this->createObject($this->path);
        $this->object->writeLinesAs($this->content, 'json');

        $this->object = $this->createObject($this->path);
        $this->object->writeLinesAs($this->content, 'json');

        $expected = '{"id":1,"name":"hoge"}' . PHP_EOL;
        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotWriteLinesAsJsonIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath, false);
        $actual = $this->object->writeLinesAs($this->content, 'json');

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteLinesAsJsonIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath);
        $this->object->writeLinesAs($this->content, 'json');
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteLinesAsJsonIfSerializerNotSet()
    {
        $this->object = $this->createObjectWithoutSerializer($this->path);
        $this->object->writeLinesAs($this->content, 'json');
    }
}
