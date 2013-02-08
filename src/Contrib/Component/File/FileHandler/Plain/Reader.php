<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\FileHandler\AbstractFileHandler;

/**
 * File line reader.
 */
class Reader extends AbstractFileHandler
{
    /**
     * Return file line (fgets() function wrapper).
     *
     * @param integer $length Length to read.
     * @return string File contents.
     */
    public function read($length = null)
    {
        if ($length === null || !is_int($length)) {
            return fgets($this->handle);
        }

        return fgets($this->handle, $length);
    }
}
