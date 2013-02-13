<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Factory\ReaderFactory;

/**
 * File reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileLineReaderTest extends \PHPUnit_Framework_TestCase
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

        return $factory->createFileLineReader($path, $options);
    }

    protected function touchUnreadableFile()
    {
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
        }

        touch($this->unreadablePath);
        chmod($this->unreadablePath, 0377);
    }

    // readLines()

    /**
     * @test
     */
    public function readLines()
    {
        $this->object = $this->createObject($this->path);

        $expected = array("hello\n", "world!");
        $actual = $this->object->readLines();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canNotReadLinesIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath, false);

        $this->assertFalse($this->object->readLines());
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadLinesIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath);

        $this->object->readLines();
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
