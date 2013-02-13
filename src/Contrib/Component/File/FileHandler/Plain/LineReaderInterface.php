<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\SeekableFileInterface;

interface LineReaderInterface extends SeekableFileInterface
{
    /**
     * Return file line.
     *
     * @param integer $length Length to read.
     * @return string File contents.
     */
    public function read($length = null);
}
