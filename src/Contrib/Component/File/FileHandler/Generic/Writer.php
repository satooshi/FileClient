<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\Plain\Writer as LineWriter;

/**
 * Generic line writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class Writer
{
    /**
     * Writer object.
     *
     * @var Contrib\Component\File\FileHandler\Plain\Writer
     */
    protected $writer;

    /**
     * Serializer object.
     *
     * @var Serializer
     */
    protected $serializer;

    /**
     * Constructor.
     *
     * @param Contrib\Component\File\FileHandler\Plain\Writer $writer
     * @param Serializer $serializer
     */
    public function __construct(LineWriter $writer, $serializer, $format)
    {
        $this->writer     = $writer;
        $this->serializer = $serializer;
        $this->format     = $format;
    }

    /**
     * Write line to file (fwrite() function wrapper).
     *
     * @param mixed   $line   Line data to write.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     */
    public function write($line, $length = null)
    {
        $serialized = $this->serializer->serialize($line, $this->format);

        return $this->writer->write($serialized, $length);
    }
}
