<?php
namespace Contrib\Component\File\Client\Plain;

interface FileReaderInterface
{
    /**
     * Return file content.
     *
     * @return string File contents.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function read();

    /**
     * Return file content exploded by new line.
     *
     * @return array File contents.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function readLines();
}
