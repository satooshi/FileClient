<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\Plain\Writer as LineWriter;
use Symfony\Component\Serializer\Serializer;

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
     * @var Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    /**
     * Constructor.
     *
     * @param Contrib\Component\File\FileHandler\Plain\Writer $writer     Line writer.
     * @param Symfony\Component\Serializer\Serializer         $serializer Serializer.
     */
    public function __construct(LineWriter $writer, Serializer $serializer, $format)
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
