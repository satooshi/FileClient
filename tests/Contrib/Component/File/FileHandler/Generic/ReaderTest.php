<?php
namespace Contrib\Component\File\FileHandler\Generic;

require_once 'SerializableEntity.php';

use Contrib\Component\File\File;
use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;
use Contrib\Component\Serializer\Factory;
use Contrib\Component\Serializer\SerializableEntity;

/**
 * Generic line reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class ReaderTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $path = 'hello.json';
    protected $content;

    protected function setUp()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        $this->content = '{"id":1,"name":"hoge"}';
        file_put_contents($this->path, $this->content);
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        // destruct
        unset($this->object);
    }

    protected function createLineReader()
    {
        $file = new File($this->path);
        $handle = $file->openForRead();

        return new LineReader($handle);
    }

    protected function createObject($type = null)
    {
        $lineReader = $this->createLineReader();
        $serializer = Factory::createSerializer();
        $format = 'json';

        return new Reader($lineReader, $serializer, $format, $type);
    }

    // read()

    /**
     * @test
     */
    public function read()
    {
        $this->object = $this->createObject();

        $expected = array('id' => 1, 'name' => 'hoge');
        $actual   = $this->object->read();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function readAsEntity()
    {
        $this->object = $this->createObject('Contrib\Component\Serializer\SerializableEntity');

        $expected = new SerializableEntity(array('id' => 1, 'name' => 'hoge'));
        $actual   = $this->object->read();

        $this->assertEquals($expected, $actual);
    }
}
