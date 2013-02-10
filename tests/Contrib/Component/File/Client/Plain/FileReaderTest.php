<?php
namespace Contrib\Component\File\Client\Plain;

/**
 * File reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileReaderTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $content;
    protected $path;
    protected $notFoundPath;
    protected $unreadablePath;
    protected $dir;
    protected $notFoundDir;

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

        $this->content = "hello\nworld!";
        file_put_contents($this->path, $this->content);
        mkdir($this->dir);
        mkdir($this->unwritableDir);

        touch($this->unreadablePath);
        touch($this->unwritablePath);
        chmod($this->unreadablePath, 0377);
        chmod($this->unwritablePath, 0577);
        chmod($this->unwritableDir, 0577);

        $this->throwException = true;
        $this->notThrowException = false;

        $this->object = new FileReader($this->path);
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

    // read()

    /**
     * @test
     */
    public function read()
    {
        $expected = $this->content;
        $actual = $this->object->read(false);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotReadIfPathIsNotReadable()
    {
        $this->object = new FileReader($this->unreadablePath, array('throwException' => false));

        $this->assertFalse($this->object->read(false));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadIfPathIsNotReadable()
    {
        $this->object = new FileReader($this->unreadablePath);

        $this->object->read(false);
    }

    /**
     * @test
     */
    public function readExploded()
    {
        $expected = array("hello\n", "world!");
        $actual = $this->object->read(true);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotReadExplodedIfPathIsNotReadable()
    {
        $this->object = new FileReader($this->unreadablePath, array('throwException' => false));

        $this->assertFalse($this->object->read(true));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionReadExplodedIfPathIsNotReadable()
    {
        $this->object = new FileReader($this->unreadablePath);

        $this->object->read(true);
    }

    // readLines()

    /**
     * @test
     */
    public function readLines()
    {
        $expected = array("hello\n", "world!");
        $actual = $this->object->readLines();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotReadLinesIfPathIsNotReadable()
    {
        $this->object = new FileReader($this->unreadablePath, array('throwException' => false));

        $this->assertFalse($this->object->readLines());
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadLinesIfPathIsNotReadable()
    {
        $this->object = new FileReader($this->unreadablePath);

        $this->object->readLines();
    }

    // getFile()

    /**
     * @test
     */
    public function getFile()
    {
        $this->assertNotNull($this->object->getFile());
    }

    // getOptions()

    /**
     * @test
     */
    public function getOptions()
    {
        $expected = array(
            'newLine'              => PHP_EOL,
            'throwException'       => true,
            'autoDetectLineEnding' => true,
        );

        $this->assertEquals($expected, $this->object->getOptions());
    }
}
