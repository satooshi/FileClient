<?php
namespace Contrib\Component\File\Client\Generic;

require_once 'SerializableEntity.php';

use Contrib\Component\File\Factory\WriterFactory;

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
        $options = array('throwException' => $throwException);
        $factory = new WriterFactory();

        return $factory->createGenericFileWriter($path, $options);
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
}
