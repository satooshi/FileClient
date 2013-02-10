<?php
namespace Contrib\Component\File\Client\Plain;

/**
 * File writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileWriterTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $content;
    protected $path;
    protected $dir;
    protected $unwritableDir;
    protected $unwritablePath;

    protected $throwException;
    protected $notThrowException;

    protected function setUp()
    {
        $this->path = './hello.txt';
        $this->dir = './test.dir';
        $this->unwritableDir = './unwritable.dir';
        $this->unwritablePath = './unwritable';


        if (is_file($this->path)) {
            unlink($this->path);
        }
        if (is_file($this->unwritablePath)) {
            unlink($this->unwritablePath);
        }
        if (is_dir($this->dir)) {
            rmdir($this->dir);
        }
        if (is_dir($this->unwritableDir)) {
            rmdir($this->unwritableDir);
        }

        mkdir($this->dir);
        mkdir($this->unwritableDir);

        touch($this->unwritablePath);
        chmod($this->unwritablePath, 0577);
        chmod($this->unwritableDir, 0577);

        $this->content = "hello\nworld!";

        $this->throwException = true;
        $this->notThrowException = false;

        $this->object = new FileWriter($this->path);
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }
        if (is_file($this->unwritablePath)) {
            unlink($this->unwritablePath);
        }
        if (is_dir($this->dir)) {
            rmdir($this->dir);
        }
        if (is_dir($this->unwritableDir)) {
            rmdir($this->unwritableDir);
        }
    }

    // write()

    /**
     * @test
     */
    public function write()
    {
        $expected = strlen($this->content);
        $actual = $this->object->write($this->content);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotWriteIfPathIsNotWritable()
    {
        $this->object = new FileWriter($this->unwritablePath, array('throwException' => false));

        $this->assertFalse($this->object->write($this->content));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteIfPathIsNotWritable()
    {
        $this->object = new FileWriter($this->unwritablePath);

        $this->object->write($this->content);
    }

    // writeLines()

    /**
     * @test
     */
    public function writeLines()
    {
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
        $this->object = new FileWriter($this->unwritablePath, array('throwException' => false));

        $lines = explode("\n", $this->content);
        $this->assertFalse($this->object->writeLines($lines));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteLinesIfPathIsNotWritable()
    {
        $this->object = new FileWriter($this->unwritablePath);

        $lines = explode("\n", $this->content);
        $this->object->writeLines($lines);
    }
}
