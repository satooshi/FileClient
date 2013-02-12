<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\File;

/**
 * File line writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class WriterTest extends \PHPUnit_Framework_TestCase
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
        $handle = $file->openForWrite();

        return new Writer($handle);
    }

    // write()

    /**
     * @test
     */
    public function write()
    {
        $this->object = $this->createObject();

        $expected = strlen($this->content) + 1; // + new line
        $actual   = $this->object->write($this->content);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function writeLength()
    {
        $this->object = $this->createObject();

        $expected = 3;
        $actual   = $this->object->write($this->content, $expected);

        $this->assertEquals($expected, $actual);
    }
}
