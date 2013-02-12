<?php
namespace Contrib\Component\File\Client\Generic;

require_once 'SerializableEntity.php';

use Contrib\Component\Serializer\Factory;
use Symfony\Component\Serializer\Serializer;

/**
 * Generic file reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileReaderTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $content;
    protected $path = './hello.json';
    protected $unreadablePath = './unreadable';

    protected function setUp()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        $this->content = '{"id":1, "name":"hoge"}';
        file_put_contents($this->path, $this->content);
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
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
        return new GenericFileReader($path, array('throwException' => $throwException));
    }

    protected function touchUnreadableFile()
    {
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
        }

        touch($this->unreadablePath);
        chmod($this->unreadablePath, 0377);
    }

    // readAs()

    /**
     * @test
     */
    public function readAsJson()
    {
        $this->object = $this->createObject($this->path);

        $expected = array(
            'id'   => 1,
            'name' => 'hoge',
        );
        $actual = $this->object->readAs('json');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function readAsJsonEntity()
    {
        $this->object = $this->createObject($this->path);

        $className = 'Contrib\Component\File\Client\Generic\SerializableEntity';
        $actual = $this->object->readAs('json', $className);

        $this->assertInstanceOf($className, $actual);
        $this->assertEquals(1, $actual->getId());
        $this->assertEquals('hoge', $actual->getName());
    }

    /**
     * @test
     */
    public function canNotReadAsJsonIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath, false);

        $className = 'Contrib\Component\File\Client\Generic\SerializableEntity';
        $actual = $this->object->readAs('json', $className);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadAsJsonIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath, true);

        $className = 'Contrib\Component\File\Client\Generic\SerializableEntity';

        $this->object->readAs('json', $className);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadAsJsonIfSerializerNotSet()
    {
        $this->object = $this->createObjectWithoutSerializer($this->path);
        $this->object->readAs('json');
    }

    // readLinesAs()

    /**
     * @test
     */
    public function readLinesAsJson()
    {
        $this->object = $this->createObject($this->path);

        $expected = array(
            array(
                'id'   => 1,
                'name' => 'hoge',
            ),
        );

        $actual = $this->object->readLinesAs('json');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotReadLinesAsJsonIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath, false);

        $className = 'Contrib\Component\File\Client\Generic\SerializableEntity';
        $actual = $this->object->readLinesAs('json', $className);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadLinesAsJsonIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath, true);

        $className = 'Contrib\Component\File\Client\Generic\SerializableEntity';

        $this->object->readLinesAs('json', $className);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadLinesAsJsonIfSerializerNotSet()
    {
        $this->object = $this->createObjectWithoutSerializer($this->path);
        $this->object->readLinesAs('json');
    }
}
