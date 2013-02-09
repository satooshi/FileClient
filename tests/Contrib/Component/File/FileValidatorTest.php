<?php
namespace Contrib\Component\File;

class FileValidatorTest extends \PHPUnit_Framework_TestCase
{
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
    }

    protected function tearDown()
    {
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
    }

    // canRead()

    /**
     * @test
     */
    public function canReadFilePath()
    {
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
        $actual = FileValidator::canRead($this->notFoundPath, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadIfPathNotFound()
    {
        FileValidator::canRead($this->notFoundPath, $this->throwException);
    }

    /**
     * @test
     */
    public function canNotReadIfPathIsDir()
    {
        $actual = FileValidator::canRead($this->dir, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadIfPathIsDir()
    {
        FileValidator::canRead($this->dir, $this->throwException);
    }

    /**
     * @test
     */
    public function canNotReadIfPathIsNotReadable()
    {
        $actual = FileValidator::canRead($this->unreadablePath, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadIfPathIsNotReadable()
    {
        FileValidator::canRead($this->unreadablePath, $this->throwException);
    }

    // canWrite()


    /**
     * @test
     */
    public function canWriteFilePath()
    {
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
        $actual = FileValidator::canWrite($this->notFoundDir, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteIfDirNotFound()
    {
        FileValidator::canWrite($this->notFoundDir, $this->throwException);
    }

    /**
     * @test
     */
    public function canNotWriteIfPathIsDir()
    {
        $actual = FileValidator::canWrite($this->dir, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteIfPathIsDir()
    {
        FileValidator::canWrite($this->dir, $this->throwException);
    }

    /**
     * @test
     */
    public function canNotWriteIfDirIsNotWritable()
    {
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
        $path = $this->unwritableDir . '/file';
        FileValidator::canWrite($path, $this->throwException);
    }

    /**
     * @test
     */
    public function canNotWriteIfPathIsNotWritable()
    {
        $actual = FileValidator::canWrite($this->unwritablePath, $this->notThrowException);

        $this->assertFalse($actual);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWriteIfPathIsNotWritable()
    {
        FileValidator::canWrite($this->unwritablePath, $this->throwException);
    }
}
