<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;

/**
 * Generic line reader.
 */
class Reader
{
    /**
     * Reader object.
     *
     * @var Contrib\Component\File\FileHandler\Plain\Reader
     */
    protected $reader;

    /**
     * Serializer object.
     *
     * @var Serializer
     */
    protected $serializer;

    /**
     * Constructor.
     *
     * @param Contrib\Component\File\FileHandler\Plain\Reader $reader
     * @param Serializer $serializer
     */
    public function __construct(LineReader $reader, $serializer)
    {
        $this->reader     = $reader;
        $this->serializer = $serializer;
    }

    /**
     * Return file line (fgets() function wrapper).
     *
     * @param integer $length Length to read.
     * @return array Deserialized data.
     */
    public function read($length = null)
    {
        $line = $this->reader->read($length);

        return $this->serializer->decode($line);
    }
}
