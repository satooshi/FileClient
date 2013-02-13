<?php
namespace Contrib\Component\File\Client\Generic;

require_once 'SerializableEntity.php';

use Contrib\Component\File\Factory\ReaderFactory;

/**
 * Generic file reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileLineReaderTest extends \PHPUnit_Framework_TestCase
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

    protected function createObject($path, $format, $type = null, $throwException = true)
    {
        $options = array('throwException' => $throwException);

        $factory = new ReaderFactory();

        return $factory->createGenericFileLineReader($path, $format, $type = null, $options);
    }

    protected function touchUnreadableFile()
    {
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
        }

        touch($this->unreadablePath);
        chmod($this->unreadablePath, 0377);
    }

    // readLinesAs()

    /**
     * @test
     */
    public function readLinesAsJson()
    {
        $this->object = $this->createObject($this->path, 'json');

        $expected = array(
            array(
                'id'   => 1,
                'name' => 'hoge',
            ),
        );

        $actual = $this->object->readLinesAs();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotReadLinesAsJsonIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $className = 'Contrib\Component\File\Client\Generic\SerializableEntity';

        $this->object = $this->createObject($this->unreadablePath, 'json', $className, false);

        $actual = $this->object->readLinesAs();

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadLinesAsJsonIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $className = 'Contrib\Component\File\Client\Generic\SerializableEntity';

        $this->object = $this->createObject($this->unreadablePath, 'json', $className, true);

        $this->object->readLinesAs();
    }
}
