<?php
namespace Contrib\Component\File\FileHandler\Plain;

interface LineWriterInterface
{
    /**
     * Write line to file.
     *
     * @param string  $line   Line to write.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     */
    public function write($line, $length = null);

    /**
     * Seek on a file pointer.
     *
     * @param integer $offset
     * @param string  $whence
     * @return integer 0 on success, -1 on failure
     */
    public function seek($offset, $whence = SEEK_SET);
}
