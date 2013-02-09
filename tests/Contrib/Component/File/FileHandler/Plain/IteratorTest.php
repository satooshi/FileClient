<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\File;

/**
 * Iterator for file read.
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
        $this->path = 'hello.txt';
        $this->content = 'hello! world.';

        if (is_file($this->path)) {
            unlink($this->path);
        }

        touch($this->path);
        file_put_contents($this->path, $this->content);

        $file = new File($this->path);
        $handle = $file->openForRead();
        $this->object = new Iterator($handle);
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
        foreach ($this->object as $i => $line) {
            $this->assertEquals(0, $i);
            $this->assertEquals($this->content, $line);
        }
    }
}
