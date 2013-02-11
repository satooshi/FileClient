<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;
use Symfony\Component\Serializer\Serializer;

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
     * @var Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    /**
     * File format.
     *
     * @var string
     */
    protected $format;

    /**
     * Deserializing class name.
     *
     * @var string
     */
    protected $type;

    /**
     * Constructor.
     *
     * @param Contrib\Component\File\FileHandler\Plain\Reader $reader     Line reader.
     * @param Symfony\Component\Serializer\Serializer         $serializer Serializer.
     * @param string                                          $format     File format.
     * @param string                                          $type       Deserializing class name.
     */
    public function __construct(LineReader $reader, Serializer $serializer, $format, $type = null)
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

        if ($line === false) {
            return false;
        }

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
