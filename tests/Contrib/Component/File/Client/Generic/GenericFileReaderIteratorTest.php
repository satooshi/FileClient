<?php
namespace Contrib\Component\File\Client\Generic;

require_once 'SerializableEntity.php';

use Contrib\Component\Serializer\Factory;
use Symfony\Component\Serializer\Serializer;

/**
 * Generic file reader iterator.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericFileReaderIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $content;
    protected $path;
    protected $unreadablePath;

    protected function setUp()
    {
        $this->path = './hello.json';
        $this->unreadablePath = './unreadable';

        if (is_file($this->path)) {
            unlink($this->path);
        }
        if (is_file($this->unreadablePath)) {
            unlink($this->unreadablePath);
        }

        $this->content = '{"id":1,"name":"hoge"}' . PHP_EOL . PHP_EOL . '{"id":2,"name":"hoge"}';
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
        $serializer = Factory::createSerializer();
        $object = $this->createObjectWithoutSerializer($path, $throwException, $skipEmptyCount, $limit, $offset);
        $object->setSerializer($serializer);

        return $object;
    }

    protected function createObjectWithoutSerializer($path, $throwException = true, $skipEmptyCount = true, $limit = 0, $offset = 0)
    {
        $options = array(
            'throwException' => $throwException,
            'skipEmptyCount' => $skipEmptyCount,
            'limit'          => $limit,
            'offset'         => $offset,
        );

        return new GenericFileReaderIterator($path, $options);
    }

    // walk()

    /**
     * @test
     */
    public function walk()
    {
        $this->object = $this->createObject($this->path);

        $expected = $this->content;
        $actual = $this->object->walk(function(){}, 'json');

        $this->assertNotNull($actual);
    }

    /**
     * @test
     */
    public function walkNotSkip1()
    {
        $this->object = $this->createObject($this->path, true, false, 1);

        $actual = $this->object->walk(function(){}, 'json');

        $this->assertNotNull($actual);
        $this->assertTrue($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function walkNotSkip3()
    {
        $this->object = $this->createObject($this->path, true, false, 3);

        $actual = $this->object->walk(function(){}, 'json');

        $this->assertNotNull($actual);
        $this->assertFalse($this->object->isSuspended());
    }


    /**
     * @test
     */
    public function walkLimit1()
    {
        $this->object = $this->createObject($this->path, true, true, 1);

        $actual = $this->object->walk(function(){}, 'json');

        $this->assertNotNull($actual);
        $this->assertTrue($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function walkLimit3()
    {
        $this->object = $this->createObject($this->path, true, true, 3);

        $actual = $this->object->walk(function(){}, 'json');

        $this->assertNotNull($actual);
        $this->assertFalse($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function canNotWalkIfPathIsNotReadable()
    {
        $this->object = $this->createObject($this->unreadablePath, false);

        $this->assertFalse($this->object->walk(function(){}, 'json'));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWalkIfPathIsNotReadable()
    {
        $this->object = $this->createObject($this->unreadablePath);

        $this->object->walk(function(){}, 'json');
    }

    /**
     * @test
     */
    public function stopWalkIfCallbackReturnFalse()
    {
        $this->object = $this->createObject($this->path);

        $actual = $this->object->walk(function () {
            return false;
        }, 'json');

        $this->assertTrue($this->object->isSuspended());
    }

    /**
     * @test
     */
    public function stopWalkLimitIfCallbackReturnFalse()
    {
        $this->object = $this->createObject($this->path, true, true, 10);

        $actual = $this->object->walk(function () {
            return false;
        }, 'json');

        $this->assertTrue($this->object->isSuspended());
    }


    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnWalkIfSerializerNotSet()
    {
        $this->object = $this->createObjectWithoutSerializer($this->path);
        $this->object->walk(function(){}, 'json');
    }

    /**
     * @test
     */
    public function suspendedIsNullOnConstruction()
    {
        $this->object = $this->createObjectWithoutSerializer($this->path);
        $this->assertNull($this->object->isSuspended());

        $this->object = $this->createObject($this->path);
        $this->assertNull($this->object->isSuspended());
    }
}
