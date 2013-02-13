<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Factory\ReaderIteratorFactory;

/**
 * File reader iterator.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileReaderLimitIteratorTest extends \PHPUnit_Framework_TestCase
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

        $this->content = "hello\n \nworld!";
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

    protected function createObject($path, $throwException = true, $skipEmptyCount = false, $limit = 0, $offset = 0)
    {
        $options = array(
            'throwException' => $throwException,
            'skipEmptyCount' => $skipEmptyCount,
            'limit'          => $limit,
            'offset'         => $offset,
        );

        $factory = new ReaderIteratorFactory();

        return $factory->createFileReaderIterator($path, $options);
    }

    protected function touchUnreadableFile()
    {
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
        }

        touch($this->unreadablePath);
        chmod($this->unreadablePath, 0377);
    }

    // walk()

    /**
     * @test
     */
    public function walk()
    {
        $this->object = $this->createObject($this->path);

        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
    }

    /**
     * @test
     */
    public function walkLimit1()
    {
        $this->object = $this->createObject($this->path, true, false, 1);

        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
        $this->assertTrue($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function walkLimit3()
    {
        $this->object = $this->createObject($this->path, true, false, 3);

        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
        $this->assertFalse($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function canNotReadIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath, false);

        $this->assertFalse($this->object->walk(function(){}));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath);

        $this->object->walk(function(){});
    }

    /**
     * @test
     */
    public function stopWalkIfCallbackReturnFalse()
    {
        $this->object = $this->createObject($this->path);

        $actual = $this->object->walk(function(){
            return false;
        });

        $this->assertTrue($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function stopWalkLimitIfCallbackReturnFalse()
    {
        $this->object = $this->createObject($this->path, true, false, 10);

        $actual = $this->object->walk(function(){
            return false;
        });

        $this->assertTrue($this->object->isSuspended());
    }

    // limit, offset

    /**
     * @test
     */
    public function invalidLimit()
    {
        $this->object = $this->createObject($this->path, true, true, "");

        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
    }

    /**
     * @test
     */
    public function invalidOffset()
    {
        $this->object = $this->createObject($this->path, true, true, 1, "");

        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
    }

    // getOptions()

    /**
     * @test
     */
    public function getDefaultOptions()
    {
        $this->object = $this->createObject($this->path);

        $expected = array(
            'newLine'              => PHP_EOL,
            'throwException'       => true,
            'autoDetectLineEnding' => true,
            'skipEmptyCount'       => false,
            'limit'                => 0,
            'offset'               => 0,
            'convertEncoding'      => true,
            'toEncoding'           => 'UTF-8',
            'fromEncoding'         => 'auto',
        );

        $this->assertEquals($expected, $this->object->getOptions());
    }

    /**
     * @test
     */
    public function getOptions()
    {
        $this->object = $this->createObject($this->path, true, false, 2, 1);

        $expected = array(
            'newLine'              => PHP_EOL,
            'throwException'       => true,
            'autoDetectLineEnding' => true,
            'skipEmptyCount'       => false,
            'limit'                => 2,
            'offset'               => 1,
            'convertEncoding'      => true,
            'toEncoding'           => 'UTF-8',
            'fromEncoding'         => 'auto',
        );

        $this->assertEquals($expected, $this->object->getOptions());
    }
}
