<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\Plain\Writer as LineWriter;

/**
 * Generic line writer.
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
    public function __construct(LineWriter $writer, $serializer)
    {
        $this->writer     = $writer;
        $this->serializer = $serializer;
    }

    /**
     * Write line to file (fwrite() function wrapper).
     *
     * @param array   $line   Line data to write.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     */
    public function write($line, $length = null)
    {
        if (is_array($line)) {
            return $this->writer->write($this->serializer->encode($line), $length);
        }

        return $this->writer->write($line, $length);
    }
}
