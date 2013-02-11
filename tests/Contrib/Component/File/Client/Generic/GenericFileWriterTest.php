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
    protected $path;
    protected $dir;
    protected $unwritableDir;
    protected $unwritablePath;

    protected $throwException;
    protected $notThrowException;

    protected function setUp()
    {
        $this->path = './hello.txt';
        $this->dir = './test.dir';
        $this->unwritableDir = './unwritable.dir';
        $this->unwritablePath = './unwritable';


        if (is_file($this->path)) {
            unlink($this->path);
        }
        if (is_file($this->unwritablePath)) {
            unlink($this->unwritablePath);
        }
        if (is_dir($this->dir)) {
            rmdir($this->dir);
        }
        if (is_dir($this->unwritableDir)) {
            rmdir($this->unwritableDir);
        }

        mkdir($this->dir);
        mkdir($this->unwritableDir);

        touch($this->unwritablePath);
        chmod($this->unwritablePath, 0577);
        chmod($this->unwritableDir, 0577);

        $this->content = array(
            new SerializableEntity(array('id' => 1, 'name' => 'hoge')),
        );

        $this->throwException = true;
        $this->notThrowException = false;

        $this->object = $this->createObject($this->path);
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }
        if (is_file($this->unwritablePath)) {
            unlink($this->unwritablePath);
        }
        if (is_dir($this->dir)) {
            rmdir($this->dir);
        }
        if (is_dir($this->unwritableDir)) {
            rmdir($this->unwritableDir);
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

    // writeAs()

    /**
     * @test
     */
    public function writeAsJson()
    {
        $expected = '{"id":1,"name":"hoge"}';

        $this->object = $this->createObject($this->path);
        $this->object->writeAs($this->content, 'json');

        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function overwriteAsJson()
    {
        $expected = '{"id":1,"name":"hoge"}';

        $this->object = $this->createObject($this->path);
        $this->object->writeAs($this->content, 'json');
        $this->object->writeAs($this->content, 'json');

        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function overwriteAsJsonToExistingFile()
    {
        $expected = '{"id":1,"name":"hoge"}';

        $this->object = $this->createObject($this->path);
        $this->object->writeAs($this->content, 'json');

        $this->object = $this->createObject($this->path);
        $this->object->writeAs($this->content, 'json');

        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotWriteAsJsonIfPathIsNotWritable()
    {
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
        $data = '{"id":1,"name":"hoge"}' . PHP_EOL;
        $expected = $data . $data;

        $this->object = $this->createObject($this->path);
        $this->object->writeLinesAs($this->content, 'json');
        $this->object->writeLinesAs($this->content, 'json');

        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function overwriteLinesAsJson()
    {
        $expected = '{"id":1,"name":"hoge"}' . PHP_EOL;

        $this->object = $this->createObject($this->path);
        $this->object->writeLinesAs($this->content, 'json');

        $this->object = $this->createObject($this->path);
        $this->object->writeLinesAs($this->content, 'json');

        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotWriteLinesAsJsonIfPathIsNotWritable()
    {
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
