<?php
namespace Contrib\Component\File\Client\Plain;

/**
 * File reader iterator.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileReaderIteratorTest extends \PHPUnit_Framework_TestCase
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

        $this->content = "hello\n \nworld!";
        touch($this->path);
        file_put_contents($this->path, $this->content);

        touch($this->unreadablePath);
        chmod($this->unreadablePath, 0377);
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

    protected function createObject($path, $throwException = true, $skipEmptyCount = true, $limit = 0, $offset = 0)
    {
        $options = array(
            'throwException' => $throwException,
            'skipEmptyCount' => $skipEmptyCount,
            'limit'          => $limit,
            'offset'         => $offset,
        );

        return new FileReaderIterator($path, $options);
    }

    // walk()

    /**
     * @test
     */
    public function walk()
    {
        $this->object = $this->createObject($this->path);

        $expected = $this->content;
        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
    }

    /**
     * @test
     */
    public function walkNotSkip1()
    {
        $this->object = $this->createObject($this->path, true, false, 1);

        $expected = $this->content;
        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);

        $this->assertTrue($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function walkNotSkip3()
    {
        $this->object = $this->createObject($this->path, true, false, 3);

        $expected = $this->content;
        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
    }

    /**
     * @test
     */
    public function walkLimit1()
    {
        $this->object = $this->createObject($this->path, true, true, 1);

        $expected = $this->content;
        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
    }

    /**
     * @test
     */
    public function walkLimit3()
    {
        $this->object = $this->createObject($this->path, true, true, 3);

        $expected = $this->content;
        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
    }

    /**
     * @test
     */
    public function canNotReadIfPathIsNotReadable()
    {
        $this->object = $this->createObject($this->unreadablePath, false);

        $this->assertFalse($this->object->walk(function(){}));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnReadIfPathIsNotReadable()
    {
        $this->object = $this->createObject($this->unreadablePath);

        $this->object->walk(function(){});
    }

    /**
     * @test
     */
    public function stopWalkIfCallbackReturnFalse()
    {
        $this->object = $this->createObject($this->path);

        $expected = $this->content;
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
        $this->object = $this->createObject($this->path, true, true, 10);

        $expected = $this->content;
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

        $expected = $this->content;
        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
    }

    /**
     * @test
     */
    public function invalidOffset()
    {
        $this->object = $this->createObject($this->path, true, true, 1, "");

        $expected = $this->content;
        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
    }

    /**
     * @test
     */
    public function setLineHandler()
    {
        $className = 'Contrib\Component\File\FileHandler\AbstractFileHandler';
        $lineHandler = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = $this->createObject($this->path);

        $this->object->setLineHandler($lineHandler);

        $actual = $this->object->getLineHandler();

        $this->assertSame($lineHandler, $actual);
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
            'skipEmptyCount'       => true,
            'limit'                => 0,
            'offset'               => 0,
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
        );

        $this->assertEquals($expected, $this->object->getOptions());
    }
}
