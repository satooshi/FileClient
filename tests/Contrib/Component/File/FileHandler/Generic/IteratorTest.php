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

    protected $path;
    protected $content;

    protected function setUp()
    {
        $this->path = 'hello.json';
        $this->content = '{"id":1,"name":"hoge"}';

        if (is_file($this->path)) {
            unlink($this->path);
        }

        touch($this->path);
        file_put_contents($this->path, $this->content);

        $reader = $this->createReader();
        $this->object = new Iterator($reader);
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

    protected function tearDown()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        // destruct
        unset($this->object);
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