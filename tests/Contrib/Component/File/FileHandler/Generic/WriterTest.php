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

    protected $path;
    protected $content;

    protected function setUp()
    {
        $this->path = 'hello.json';
        $this->content = '{"id":1,"name":"hoge"}' . "\n";

        if (is_file($this->path)) {
            unlink($this->path);
        }

        // construction
        $this->object = $this->createWriter();
    }

    protected function createLineWriter()
    {
        $file = new File($this->path);
        $handle = $file->openForWrite();

        return new LineWriter($handle);
    }

    protected function createWriter()
    {
        $lineWriter = $this->createLineWriter();
        $serializer = Factory::createSerializer();
        $format = 'json';

        return new Writer($lineWriter, $serializer, $format);
    }

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        // destruct
        unset($this->object);
    }

    // write()

    /**
     * @test
     */
    public function write()
    {
        $expected = strlen($this->content);

        $data     = array('id' => 1, 'name' => 'hoge');
        $actual   = $this->object->write($data);

        $this->assertEquals($expected, $actual);

        $content = file_get_contents($this->path);
        $this->assertEquals($this->content, $content);
    }
}
