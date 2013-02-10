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
    protected $unreadablePath;

    protected function setUp()
    {
        $this->path = './hello.txt';
        $this->unreadablePath = './unreadable';

        if (is_file($this->path)) {
            unlink($this->path);
        }
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
        }

        $this->content = "hello\nworld!";
        file_put_contents($this->path, $this->content);

        touch($this->unreadablePath);
        chmod($this->unreadablePath, 0377);

        $this->object = new FileReader($this->path);
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
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
