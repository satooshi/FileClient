<?php
namespace Contrib\Component\File;

/**
 * File handle.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $path;
    protected $notFoundPath;
    protected $unreadablePath;
    protected $dir;
    protected $notFoundDir;
    protected $unwritableDir;
    protected $unwritablePath;

    protected $throwException;
    protected $notThrowException;

    protected function setUp()
    {
        $this->path = './hello.txt';
        $this->notFoundPath = './notfound';
        $this->unreadablePath = './unreadable';
        $this->dir = './test.dir';
        $this->notFoundDir = './notfound/dir';
        $this->unwritableDir = './unwritable.dir';
        $this->unwritablePath = './unwritable';


        if (is_file($this->path)) {
            unlink($this->path);
        }
        if (is_file($this->notFoundPath)) {
            unlink($this->notFoundPath);
        }
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
        }
        if (is_file($this->unwritablePath)) {
            unlink($this->unwritablePath);
        }
        if (is_dir($this->dir)) {
            rmdir($this->dir);
        }
        if (is_dir($this->notFoundDir)) {
            rmdir($this->notFoundDir);
        }
        if (is_dir($this->unwritableDir)) {
            rmdir($this->unwritableDir);
        }

        touch($this->path);
        mkdir($this->dir);
        mkdir($this->unwritableDir);

        touch($this->unreadablePath);
        touch($this->unwritablePath);
        chmod($this->unreadablePath, 0377);
        chmod($this->unwritablePath, 0577);
        chmod($this->unwritableDir, 0577);

        $this->throwException = true;
        $this->notThrowException = false;

        $this->object = new File($this->path, $this->throwException);
    }

    // isReadable()

    /**
     * @test
     */
    public function isReadable()
    {
        $actual = $this->object->isReadable();

        $this->assertTrue($actual);
    }

    // isWritable()

    /**
     * @test
     */
    public function isWritable()
    {
        $actual = $this->object->isWritable();

        $this->assertTrue($actual);
    }

    // openForRead()

    /**
     * @test
     */
    public function canOpenForRead()
    {
        $actual = $this->object->openForRead();

        $this->assertTrue(is_resource($actual));
    }

    /**
     * @test
     */
    public function canNotOpenForReadIfPathIsNotReadable()
    {
        $this->object = new File($this->unreadablePath, $this->notThrowException);
        $actual = $this->object->openForRead();

        $this->assertFalse($actual);
    }

    // openForWrite()

    /**
     * @test
     */
    public function canOpenForWrite()
    {
        $actual = $this->object->openForWrite();

        $this->assertTrue(is_resource($actual));
    }

    /**
     * @test
     */
    public function canNotOpenForWriteIfPathIsNotWritable()
    {
        $this->object = new File($this->unwritablePath, $this->notThrowException);
        $actual = $this->object->openForWrite();

        $this->assertFalse($actual);
    }

    // openForAppend()

    /**
     * @test
     */
    public function canOpenForAppend()
    {
        $actual = $this->object->openForAppend();

        $this->assertTrue(is_resource($actual));
    }

    /**
     * @test
     */
    public function canNotOpenForAppendIfPathIsNotWritable()
    {
        $this->object = new File($this->unwritablePath, $this->notThrowException);
        $actual = $this->object->openForAppend();

        $this->assertFalse($actual);
    }
}
