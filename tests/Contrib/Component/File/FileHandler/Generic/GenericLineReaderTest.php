<?php
namespace Contrib\Component\File\FileHandler\Generic;

require_once 'SerializableEntity.php';

use Contrib\Component\Serializer\SerializableEntity;
use Contrib\Component\File\Factory\ReaderFactory;

/**
 * Generic line reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GenericLineReaderTest extends \PHPUnit_Framework_TestCase
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
        $factory = new ReaderFactory();

        return $factory->createGenericLineReader($path, $format, $type, $options);
    }

    // read()

    /**
     * @test
     */
    public function read()
    {
        $this->object = $this->createObject($this->path, 'json');

        $expected = array('id' => 1, 'name' => 'hoge');
        $actual   = $this->object->read();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function readAsEntity()
    {
        $this->object = $this->createObject($this->path, 'json', 'Contrib\Component\Serializer\SerializableEntity');

        $expected = new SerializableEntity(array('id' => 1, 'name' => 'hoge'));
        $actual   = $this->object->read();

        $this->assertEquals($expected, $actual);
    }
}
