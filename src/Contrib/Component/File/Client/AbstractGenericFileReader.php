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
    abstract protected function createLineReader($handle, $format = null, $type = null);
}
