<?php
namespace Contrib\Component\File\Client\Generic;

class SerializableEntity
{
    protected $id;
    protected $name;

    public function __construct($data = array())
    {
        if (is_array($data)) {
            foreach ($data as $property => $value) {
                if (property_exists($this, $property)) {
                    $this->$property = $value;
                }
            }
        }
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
