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
     * Initialize line writer.
     *
     * @param string $format
     */
    abstract protected function initWriter($format = null);

    /**
     * Create line writer object.
     *
     * @param resource $handle File handle.
     * @param string   $format File format.
     * @return \Contrib\Component\File\FileHandler\Plain\Writer
     */
    protected function createLineWriter($handle, $format = null)
    {
        return new LineWriter($handle, $this->options['newLine']);
    }
}
