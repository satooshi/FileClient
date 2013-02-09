<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\File;

/**
 * File line reader.
 */
class ReaderTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $path;
    protected $content;

    protected function setUp()
    {
        $this->path = 'hello.txt';
        $this->content = 'hello! world.';

        if (is_file($this->path)) {
            unlink($this->path);
        }

        touch($this->path);
        file_put_contents($this->path, $this->content);

        $file = new File($this->path);
        $handle = $file->openForRead();
        $this->object = new Reader($handle);
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        // destruct
        unset($this->object);
    }

    // read()

    /**
     * @test
     */
    public function read()
    {
        $expected = $this->content;
        $actual   = $this->object->read();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function readLength()
    {
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
        $expected = 0;
        $actual   = $this->object->seek(0);

        $this->assertEquals($expected, $actual);
    }
}
