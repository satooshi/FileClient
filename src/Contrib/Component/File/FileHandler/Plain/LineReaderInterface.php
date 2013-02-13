<?php
namespace Contrib\Component\File\FileHandler\Plain;

interface LineReaderInterface
{
    /**
     * Return file line.
     *
     * @param integer $length Length to read.
     * @return string File contents.
     */
    public function read($length = null);

    /**
     * Seek on a file pointer.
     *
     * @param integer $offset
     * @param string  $whence
     * @return integer 0 on success, -1 on failure
     */
    public function seek($offset, $whence = SEEK_SET);
}
