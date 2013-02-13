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
     * @return boolean true on success, false on failure.
     * @throws \RuntimeException If file handle is not set.
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Return file.
     *
     * @return \Contrib\Component\File\File
     */
    public function getFile();
}
