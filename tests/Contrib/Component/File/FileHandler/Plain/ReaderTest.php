<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\File;

/**
 * File line reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class ReaderTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $path = 'hello.txt';
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

        // destruct
        unset($this->object);
    }

    protected function createObject()
    {
        $file = new File($this->path);
        $handle = $file->openForRead();

        return new Reader($handle);
    }

    // read()

    /**
     * @test
     */
    public function read()
    {
        $this->object = $this->createObject();

        $expected = $this->content;
        $actual   = $this->object->read();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function readLength()
    {
        $this->object = $this->createObject();

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
        $this->object = $this->createObject();

        $expected = 0;
        $actual   = $this->object->seek(0);

        $this->assertEquals($expected, $actual);
    }
}
