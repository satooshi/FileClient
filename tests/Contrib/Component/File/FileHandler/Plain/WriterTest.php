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
        $handle = $file->openForWrite();
        $this->object = new Writer($handle);
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        // destruct
        unset($this->object);
    }

    // write()

    /**
     * @test
     */
    public function write()
    {
        $expected = strlen($this->content) + 1; // + new line
        $actual   = $this->object->write($this->content);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function writeLength()
    {
        $expected = 3;
        $actual   = $this->object->write($this->content, $expected);

        $this->assertEquals($expected, $actual);
    }
}
