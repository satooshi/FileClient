<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\Factory\ReaderFactory;

/**
 * File line reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class LineReaderTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $path = 'hello.txt';
    protected $unreadablePath = './unreadable';
    protected $content;

    protected function setUp()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        $this->content = 'hello! world.';
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

        // destruct
        unset($this->object);
    }

    protected function createObject($path, array $options = array())
    {
        $factory = new ReaderFactory();

        return $factory->createLineReader($path, $options);
    }

    // read()

    /**
     * @test
     */
    public function read()
    {
        $this->object = $this->createObject($this->path);

        $expected = $this->content;
        $actual   = $this->object->read();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function readLength()
    {
        $this->object = $this->createObject($this->path);

        $chars    = 3;
        $expected = 'hel';
        $actual   = $this->object->read($chars + 1);

        $this->assertEquals($expected, $actual);
    }

    // seek()

    /**
     * @test
     */
    public function seek()
    {
        $this->object = $this->createObject($this->path);

        $actual = $this->object->seek(0);

        $this->assertTrue($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnSeekIfPathIsNotReadable()
    {
        $options = array('throwException' => false);
        $this->object = $this->createObject($this->unreadablePath, $options);

        $actual = $this->object->seek(0);

        $this->assertFalse($actual);
    }
}
