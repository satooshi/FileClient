<?php
namespace Contrib\Component\File\Client\Plain;

interface FileWriterInterface
{
    /**
     * Write lines to file.
     *
     * @param string $lines Lines to write.
     * @return integer Number of bytes written to the file.
     */
    public function write($lines);
}
