<?php
namespace Contrib\Component\File\Client\Generic;

require_once 'SerializableEntity.php';

use Contrib\Component\Serializer\Factory;
use Symfony\Component\Serializer\Serializer;
use Contrib\Component\File\Factory\ReaderIteratorFactory;

/**
 * Generic file reader iterator.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileReaderLimitIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $content;
    protected $path = './hello.json';
    protected $unreadablePath = './unreadable';

    protected function setUp()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        $this->content = '{"id":1,"name":"hoge"}' . PHP_EOL . PHP_EOL . '{"id":2,"name":"hoge"}';
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

    protected function createObject($path, $format, $type = null, $throwException = true, $skipEmptyCount = false, $limit = 0, $offset = 0)
    {
        $options = array(
            'throwException' => $throwException,
            'skipEmptyCount' => $skipEmptyCount,
            'limit'          => $limit,
            'offset'         => $offset,
        );

        $factory = new ReaderIteratorFactory();

        return $factory->createGenericFileReaderIterator($path, $format, $type, $options);
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
    public function walkAsJson()
    {
        $this->object = $this->createObject($this->path, 'json');

        $expected = $this->content;
        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
    }

    /**
     * @test
     */
    public function walkAsJsonLimit1()
    {
        $this->object = $this->createObject($this->path, 'json', null, true, false, 1);

        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
        $this->assertTrue($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function walkAsJsonLimit3()
    {
        $this->object = $this->createObject($this->path, 'json', null, true, false, 3);

        $actual = $this->object->walk(function(){});

        $this->assertNotNull($actual);
        $this->assertFalse($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function canNotWalkIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath, 'json', null, false);

        $this->assertFalse($this->object->walk(function(){}));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWalkIfPathIsNotReadable()
    {
        $this->touchUnreadableFile();

        $this->object = $this->createObject($this->unreadablePath, 'json');

        $this->object->walk(function(){});
    }

    /**
     * @test
     */
    public function stopWalkAsJsonIfCallbackReturnFalse()
    {
        $this->object = $this->createObject($this->path, 'json');

        $actual = $this->object->walk(
            function () {
                return false;
            }
        );

        $this->assertTrue($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function stopWalkAsJsonLimitIfCallbackReturnFalse()
    {
        $this->object = $this->createObject($this->path, 'json', null, true, false, 10);

        $actual = $this->object->walk(
            function () {
                return false;
            }
        );

        $this->assertTrue($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function suspendedIsFalseOnConstruction()
    {
        $this->object = $this->createObject($this->path, 'json');

        $this->assertFalse($this->object->isSuspended());
    }
}
