<?php
namespace Contrib\Component\File;

interface SeekableFileInterface
{
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
