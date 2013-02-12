<?php
namespace Contrib\Component\File;

/**
 * File access validator.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $path = './hello.txt';
    protected $notFoundPath = './notfound';
    protected $unreadablePath = './unreadable';
    protected $dir = './test.dir';
    protected $notFoundDir = './notfound/dir';
    protected $unwritableDir = './unwritable.dir';
    protected $unwritablePath = './unwritable';

    protected $throwException = true;
    protected $notThrowException = false;

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
        if (is_dir($this->dir)) {
            rmdir($this->dir);
        }
        if (is_dir($this->unwritableDir)) {
            rmdir($this->unwritableDir);
        }
    }

    protected function touchFile()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        touch($this->path);
    }

    protected function mkdirDir()
    {
        if (is_dir($this->dir)) {
            rmdir($this->dir);
        }

        mkdir($this->dir);
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

    protected function mkdirUnwritableDir()
    {
        if (is_dir($this->unwritableDir)) {
            rmdir($this->unwritableDir);
        }

        mkdir($this->unwritableDir);
        chmod($this->unwritableDir, 0577);
    }

    protected function unlinkNotFoundPath()
    {
        if (is_file($this->notFoundPath)) {
            unlink($this->notFoundPath);
        }
    }

    protected function rmdirNotFoundDir()
    {
        if (is_dir($this->notFoundDir)) {
            rmdir($this->notFoundDir);
        }
    }

    // canRead()

    /**
     * @test
     */
    public function canReadFilePath()
    {
        $this->touchFile();

        $actual = FileValidator::canRead($this->path, $this->notThrowException);

        $this->assertTrue($actual);

        $actual = FileValidator::canRead($this->path, $this->throwException);

        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function canNotReadIfPathNotFound()
    {
        $this->unlinkNotFoundPath();

        $actual = FileValidator::canRead($this->notFoundPath, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadIfPathNotFound()
    {
        $this->unlinkNotFoundPath();

        FileValidator::canRead($this->notFoundPath, $this->throwException);
    }

    /**
     * @test
     */
    public function canNotReadIfPathIsDir()
    {
        $this->mkdirDir();

        $actual = FileValidator::canRead($this->dir, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadIfPathIsDir()
    {
        $this->mkdirDir();

        FileValidator::canRead($this->dir, $this->throwException);
    }

    /**
     * @test
     */
    public function canNotReadIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $actual = FileValidator::canRead($this->unreadablePath, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        FileValidator::canRead($this->unreadablePath, $this->throwException);
    }

    // canWrite()


    /**
     * @test
     */
    public function canWriteFilePath()
    {
        $this->touchFile();

        $actual = FileValidator::canWrite($this->path, $this->notThrowException);

        $this->assertTrue($actual);

        $actual = FileValidator::canWrite($this->path, $this->throwException);

        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function canWriteIfPathNotFound()
    {
        $this->unlinkNotFoundPath();

        $actual = FileValidator::canWrite($this->notFoundPath, $this->notThrowException);

        $this->assertTrue($actual);

        $actual = FileValidator::canWrite($this->notFoundPath, $this->throwException);

        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function canNotWriteIfDirNotFound()
    {
        $this->rmdirNotFoundDir();

        $actual = FileValidator::canWrite($this->notFoundDir, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteIfDirNotFound()
    {
        $this->rmdirNotFoundDir();

        FileValidator::canWrite($this->notFoundDir, $this->throwException);
    }

    /**
     * @test
     */
    public function canNotWriteIfPathIsDir()
    {
        $this->mkdirDir();

        $actual = FileValidator::canWrite($this->dir, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteIfPathIsDir()
    {
        $this->mkdirDir();

        FileValidator::canWrite($this->dir, $this->throwException);
    }

    /**
     * @test
     */
    public function canNotWriteIfDirIsNotWritable()
    {
        $this->mkdirUnwritableDir();

        $path = $this->unwritableDir . '/file';
        $actual = FileValidator::canWrite($path, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteIfDirIsNotWritable()
    {
        $this->mkdirUnwritableDir();

        $path = $this->unwritableDir . '/file';
        FileValidator::canWrite($path, $this->throwException);
    }

    /**
     * @test
     */
    public function canNotWriteIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        $actual = FileValidator::canWrite($this->unwritablePath, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteIfPathIsNotWritable()
    {
        $this->touchUnwritableFile();

        FileValidator::canWrite($this->unwritablePath, $this->throwException);
    }
}
