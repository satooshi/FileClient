<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\SeekableFileInterface;

interface LineWriterInterface extends SeekableFileInterface
{
    /**
     * Write line to file.
     *
     * @param string  $line   Line to write.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     */
    public function write($line, $length = null);
}
