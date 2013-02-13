<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\File;
use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;
use Contrib\Component\Serializer\Factory;
use Contrib\Component\File\Factory\ReaderIteratorFactory;

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

    protected function createObject($path, $format, $type = null, array $options = array())
    {
        $factory = new ReaderIteratorFactory();

        return $factory->createGenericLineIterator($path, $format, $type, $options);
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
        $this->object = $this->createObject($this->path, 'json');

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
