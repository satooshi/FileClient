<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\Factory\WriterFactory;

/**
 * Generic line writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericLineWriterTest extends \PHPUnit_Framework_TestCase
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

    protected function createObject($path, $format, array $options = array())
    {
        $factory = new WriterFactory();

        return $factory->createGenericLineWriter($path, $format, $options);
    }

    // write()

    /**
     * @test
     */
    public function write()
    {
        $this->object = $this->createObject($this->path, 'json');

        $expected = strlen($this->content);

        $data     = array('id' => 1, 'name' => 'hoge');
        $actual   = $this->object->write($data);

        $this->assertEquals($expected, $actual);

        $content = file_get_contents($this->path);
        $this->assertEquals($this->content, $content);
    }
}
