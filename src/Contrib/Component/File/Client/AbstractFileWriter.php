<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\File\FileHandler\Plain\Writer as LineWriter;

/**
 * Abstract file writer.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractFileWriter extends AbstractFileClient
{
    // API

    /**
     * Write lines to file (fule_put_contents() function wrapper).
     *
     * @param string $lines Lines to write.
     * @return integer Number of bytes written to the file.
     */
    abstract public function write($lines);

    /**
     * Write lines to file (fwrite() function wrapper).
     *
     * @param array   $lines  Lines data to write.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     */
    public function writeLines(array $lines, $length = null)
    {
        if (!$this->initWriter()) {
            return false;
        }

        $bytes = 0;

        foreach ($lines as $line) {
            $bytes += $this->lineHandler->write($line, $length);
        }

        return $bytes;
    }

    // internal method

    /**
     * Open file for write.
     *
     * @return resource File handle.
     */
    abstract protected function open();

    /**
     * Initialize line writer.
     *
     * @return void
     */
    protected function initWriter()
    {
        if (!isset($this->lineHandler)) {
            $handle = $this->open();

            if ($handle === false) {
                return false;
            }

            $this->lineHandler = $this->createLineWriter($handle);
        }

        return true;
    }

    /**
     * Create line writer object.
     *
     * @param resource $handle File handle.
     * @return \Contrib\Component\File\FileHandler\Plain\Writer
     */
    protected function createLineWriter($handle)
    {
        return new LineWriter($handle, $this->options['newLine']);
    }
}
