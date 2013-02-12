<?php
namespace Contrib\Component\File\Client\Plain;

/**
 * File appender.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileAppenderTest extends \PHPUnit_Framework_TestCase
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

        $this->content = "hello\nworld!";
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
        return new FileAppender($path, array('throwException' => $throwException));
    }

    protected function touchUnwritableFile()
    {
        if (is_file($this->unwritablePath)) {
            unlink($this->unwritablePath);
        }

        touch($this->unwritablePath);
        chmod($this->unwritablePath, 0577);
    }

    // write()

    /**
     * @test
     */
    public function write()
    {
        $this->object = $this->createObject($this->path);

        $expected = strlen($this->content);
        $actual = $this->object->write($this->content);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotWriteIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath, false);

        $this->assertFalse($this->object->write($this->content));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath);

        $this->object->write($this->content);
    }

    // writeLines()

    /**
     * @test
     */
    public function writeLines()
    {
        $this->object = $this->createObject($this->path);

        $expected = strlen($this->content) + 1;
        $lines = explode("\n", $this->content);
        $actual = $this->object->writeLines($lines);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotWriteLinesIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath, false);

        $lines = explode("\n", $this->content);
        $this->assertFalse($this->object->writeLines($lines));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteLinesIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath);

        $lines = explode("\n", $this->content);
        $this->object->writeLines($lines);
    }
}
