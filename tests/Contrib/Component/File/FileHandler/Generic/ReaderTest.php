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

    protected $path;
    protected $content;

    protected function setUp()
    {
        $this->path = 'hello.txt';
        $this->content = "id:1\tname:hoge";

        if (is_file($this->path)) {
            unlink($this->path);
        }

        //touch($this->path);
        file_put_contents($this->path, $this->content);

        // construction
        $this->object = $this->createReader();
    }

    protected function createLineReader()
    {
        $file = new File($this->path);
        $handle = $file->openForRead();

        return new LineReader($handle);
    }

    protected function createReader($type = null)
    {
        $lineReader = $this->createLineReader();
        $serializer = Factory::createSerializer();
        $format = 'ltsv';

        return new Reader($lineReader, $serializer, $format, $type);
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        // destruct
        unset($this->object);
    }

    // read()

    /**
     * @test
     */
    public function read()
    {
        $expected = array('id' => 1, 'name' => 'hoge');
        $actual   = $this->object->read();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function readAsEntity()
    {
        $this->object = $this->createReader('Contrib\Component\Serializer\SerializableEntity');

        $expected = new SerializableEntity(array('id' => 1, 'name' => 'hoge'));
        $actual   = $this->object->read();

        $this->assertEquals($expected, $actual);
    }
}
