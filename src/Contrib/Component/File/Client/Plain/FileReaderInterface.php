<?php
namespace Contrib\Component\File\Client\Plain;

interface FileReaderInterface
{
    /**
     * Return file content.
     *
     * @param boolean $explode Whether to explode by new line.
     * @return string File contents
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function read($explode = false);
}
