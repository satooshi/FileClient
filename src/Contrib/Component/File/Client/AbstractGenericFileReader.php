<?php
namespace Contrib\Component\File\Client;

/**
 * Abstract generic file reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractGenericFileReader extends AbstractGenericFileClient
{
    // internal method

    /**
     * Create generic line reader.
     *
     * @param resource $handle
     * @param string   $format
     * @param string   $type
     */
    abstract protected function createLineReader($handle, $format, $type = null);

    /**
     * Initialize generic line reader.
     *
     * @param string $format
     * @param string $type
     * @return boolean
     */
    protected function initReader($format, $type = null)
    {
        if (!isset($this->lineHandler)) {
            $handle = $this->file->openForRead();

            if ($handle === false) {
                return false;
            }

            $this->lineHandler = $this->createLineReader($handle, $format, $type);
        }

        return true;
    }
}
