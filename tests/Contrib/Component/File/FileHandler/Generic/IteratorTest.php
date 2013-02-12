<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\File;
use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;
use Contrib\Component\Serializer\Factory;

/**
 * Generic line iterator.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class IteratorTest extends \PHPUnit_Framework_TestCase
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

    protected function createReader($type = null)
    {
        $lineReader = $this->createLineReader();
        $serializer = Factory::createSerializer();
        $format = 'json';

        return new Reader($lineReader, $serializer, $format, $type);
    }

    protected function createObject($type = null)
    {
        $reader = $this->createReader($type);

        return new Iterator($reader);
    }

    // rewind()
    // valid()
    // current()
    // key()
    // next()

    /**
     * @test
     */
    public function canIterate()
    {
        $this->object = $this->createObject();

        $expected = array(
            'id'   => 1,
            'name' => 'hoge',
        );

        foreach ($this->object as $i => $line) {
            if (!empty($line)) {
                break;
            }
            $this->assertEquals(0, $i);
            $this->assertEquals($expected, $line);
        }
    }
}
