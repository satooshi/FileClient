<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;

/**
 * Generic line reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
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
    public function __construct(LineReader $reader, $serializer, $format, $type = null)
    {
        $this->reader     = $reader;
        $this->serializer = $serializer;
        $this->format     = $format;
        $this->type       = $type;
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

        if ($this->type === null) {
            return $this->serializer->decode($line, $this->format);
        }

        return $this->serializer->deserialize($line, $this->type, $this->format);
    }

    /**
     * Seek on a file pointer.
     *
     * @param integer $offset
     * @param string  $whence
     * @return integer 0 on success, -1 on failure
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        return $this->reader->seek($offset, $whence);
    }
}
