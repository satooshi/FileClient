<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Factory\ReaderFactory;

/**
 * File reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileReaderTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $content;
    protected $path = './hello.txt';
    protected $unreadablePath = './unreadable';

    protected function setUp()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        $this->content = "hello\nworld!";
        file_put_contents($this->path, $this->content);
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

    protected function createObject($path, $throwException = true)
    {
        $options = array('throwException' => $throwException);
        $factory = new ReaderFactory();

        return $factory->createFileReader($path, $options);

        return new FileReader($path, array('throwException' => $throwException));
    }

    protected function touchUnreadableFile()
    {
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
        }

        touch($this->unreadablePath);
        chmod($this->unreadablePath, 0377);
    }

    // read()

    /**
     * @test
     */
    public function read()
    {
        $this->object = $this->createObject($this->path);

        $expected = $this->content;
        $actual = $this->object->read(false);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotReadIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath, false);

        $this->assertFalse($this->object->read(false));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath);

        $this->object->read(false);
    }

    /**
     * @test
     */
    public function readExploded()
    {
        $this->object = $this->createObject($this->path);

        $expected = array("hello", "world!");
        $actual = $this->object->readLines();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotReadExplodedIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath, false);

        $this->assertFalse($this->object->readLines());
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionReadExplodedIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath);

        $this->object->readLines();
    }

    // getFile()

    /**
     * @test
     */
    public function getFile()
    {
        $this->object = $this->createObject($this->path);

        $this->assertNotNull($this->object->getFile());
    }

    // getOptions()

    /**
     * @test
     */
    public function getOptions()
    {
        $this->object = $this->createObject($this->path);

        $expected = array(
            'newLine'              => "\n",
            'throwException'       => true,
            'autoDetectLineEnding' => true,
            'convertEncoding'      => true,
            'toEncoding'           => 'UTF-8',
            'fromEncoding'         => 'auto',
        );

        $this->assertEquals($expected, $this->object->getOptions());
    }
}
