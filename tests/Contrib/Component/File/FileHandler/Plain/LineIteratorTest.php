<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\Factory\ReaderIteratorFactory;

/**
 * LineIterator for file read.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class LineIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $path = 'hello.txt';
    protected $content;

    protected function setUp()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        $this->content = "hello! world";
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

    protected function createObject($path, array $options = array())
    {
        $factory = new ReaderIteratorFactory();

        return $factory->createLineIterator($path, $options);
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
        $this->object = $this->createObject($this->path);

        //rewind valid current key next
        foreach ($this->object as $i => $line) {
            $this->assertEquals(0, $i);
            $this->assertEquals($this->content, $line);
        }
    }
}
