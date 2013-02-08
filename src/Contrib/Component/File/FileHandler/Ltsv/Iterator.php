<?php
namespace Contrib\Component\File\FileHandler\Ltsv;

use Contrib\Component\File\FileHandler\Plain\Iterator as LineIterator;

/**
 * Iterator for LTSV file read.
 */
class Iterator extends LineIterator
{
    /**
     * Create Reader object.
     *
     * @param resource $handle
     * @return \Contrib\Component\File\FileHandler\Ltsv\Reader
     */
    protected function createReader($handle)
    {
        return new Reader($handle);
    }
}
