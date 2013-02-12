<?php
namespace Contrib\Component\File\FileHandler\Generic;

require_once 'SerializableEntity.php';

use Contrib\Component\File\File;
use Contrib\Component\File\FileHandler\Plain\Writer as LineWriter;
use Contrib\Component\Serializer\Factory;
use Contrib\Component\Serializer\SerializableEntity;

/**
 * Generic line writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class WriterTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $path = 'hello.json';
    protected $content;

    protected function setUp()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        $this->content = '{"id":1,"name":"hoge"}' . "\n";
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        // destruct
        unset($this->object);
    }

    protected function createLineWriter()
    {
        $file = new File($this->path);
        $handle = $file->openForWrite();

        return new LineWriter($handle);
    }

    protected function createObject()
    {
        $lineWriter = $this->createLineWriter();
        $serializer = Factory::createSerializer();
        $format = 'json';

        return new Writer($lineWriter, $serializer, $format);
    }

    // write()

    /**
     * @test
     */
    public function write()
    {
        $this->object = $this->createObject();

        $expected = strlen($this->content);

        $data     = array('id' => 1, 'name' => 'hoge');
        $actual   = $this->object->write($data);

        $this->assertEquals($expected, $actual);

        $content = file_get_contents($this->path);
        $this->assertEquals($this->content, $content);
    }
}
