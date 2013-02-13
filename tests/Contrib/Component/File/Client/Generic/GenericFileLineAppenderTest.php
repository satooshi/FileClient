<?php
namespace Contrib\Component\File\Client\Generic;

require_once 'SerializableEntity.php';

use Contrib\Component\File\Factory\WriterFactory;

/**
 * Generic file appender.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileLineAppenderTest extends \PHPUnit_Framework_TestCase
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

    protected function createObject($path, $format, $throwException = true)
    {
        $options = array('throwException' => $throwException);
        $factory = new WriterFactory();

        return $factory->createGenericFileLineAppender($path, $format, $options);
    }

    protected function touchUnwritableFile()
    {
        if (is_file($this->unwritablePath)) {
            unlink($this->unwritablePath);
        }

        touch($this->unwritablePath);
        chmod($this->unwritablePath, 0577);
    }

    // writeLinesAs()

    /**
     * @test
     */
    public function writeLinesAsJson()
    {
        $this->object = $this->createObject($this->path, 'json');
        $this->object->writeLinesAs($this->content);

        $expected = '{"id":1,"name":"hoge"}' . PHP_EOL;
        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function appendLinesAsJson()
    {
        $this->object = $this->createObject($this->path, 'json');
        $this->object->writeLinesAs($this->content);
        $this->object->writeLinesAs($this->content);

        $data = '{"id":1,"name":"hoge"}' . PHP_EOL;
        $expected = $data . $data;
        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function appendLinesAsJsonToExistingFile()
    {
        $this->object = $this->createObject($this->path, 'json');
        $this->object->writeLinesAs($this->content);

        $this->object = $this->createObject($this->path, 'json');
        $this->object->writeLinesAs($this->content);

        $data = '{"id":1,"name":"hoge"}' . PHP_EOL;
        $expected = $data . $data;
        $actual = file_get_contents($this->path);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotWriteLinesAsJsonIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath, 'json', false);
        $actual = $this->object->writeLinesAs($this->content);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteLinesAsJsonIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath, 'json');
        $this->object->writeLinesAs($this->content);
    }
}
