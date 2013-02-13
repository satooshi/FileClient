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
