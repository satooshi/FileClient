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

    protected $path = './hello.txt';
    protected $unreadablePath = './unreadable';
    protected $unwritablePath = './unwritable';

    protected $throwException = true;
    protected $notThrowException = false;

    protected function setUp()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        touch($this->path);
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
        }
        if (is_file($this->unwritablePath)) {
            unlink($this->unwritablePath);
        }
    }

    protected function createObject($path, $throwException = true)
    {
        return new File($path, $throwException);
    }

    protected function touchUnreadableFile()
    {
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
        }

        touch($this->unreadablePath);
        chmod($this->unreadablePath, 0377);
    }

    protected function touchUnwritableFile()
    {
        if (is_file($this->unwritablePath)) {
            unlink($this->unwritablePath);
        }

        touch($this->unwritablePath);
        chmod($this->unwritablePath, 0577);
    }

    // isReadable()

    /**
     * @test
     */
    public function isReadable()
    {
        $this->object = $this->createObject($this->path);

        $actual = $this->object->isReadable();

        $this->assertTrue($actual);
    }

    // isWritable()

    /**
     * @test
     */
    public function isWritable()
    {
        $this->object = $this->createObject($this->path);

        $actual = $this->object->isWritable();

        $this->assertTrue($actual);
    }

    // openForRead()

    /**
     * @test
     */
    public function canOpenForRead()
    {
        $this->object = $this->createObject($this->path);

        $actual = $this->object->openForRead();

        $this->assertTrue(is_resource($actual));
    }

    /**
     * @test
     */
    public function canNotOpenForReadIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath, false);

        $actual = $this->object->openForRead();

        $this->assertFalse($actual);
    }

    // openForWrite()

    /**
     * @test
     */
    public function canOpenForWrite()
    {
        $this->object = $this->createObject($this->path);

        $actual = $this->object->openForWrite();

        $this->assertTrue(is_resource($actual));
    }

    /**
     * @test
     */
    public function canNotOpenForWriteIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath, false);

        $actual = $this->object->openForWrite();

        $this->assertFalse($actual);
    }

    // openForAppend()

    /**
     * @test
     */
    public function canOpenForAppend()
    {
        $this->object = $this->createObject($this->path);

        $actual = $this->object->openForAppend();

        $this->assertTrue(is_resource($actual));
    }

    /**
     * @test
     */
    public function canNotOpenForAppendIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $this->object = $this->createObject($this->unwritablePath, false);

        $actual = $this->object->openForAppend();

        $this->assertFalse($actual);
    }

    // getPath()

    /**
     * @test
     */
    public function getPath()
    {
        $this->object = $this->createObject($this->path);

        $this->assertEquals($this->path, $this->object->getPath());
    }

    // throwException()

    /**
     * @test
     */
    public function throwExceptionIsTrue()
    {
        $this->object = $this->createObject($this->path);

        $this->assertTrue($this->object->throwException());
    }
}
