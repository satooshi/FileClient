<?php
namespace Contrib\Component\File\FileHandler;

/**
 * Abstract File handler
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractFileHandler
{
    /**
     * File handle.
     *
     * @var resource
     */
    protected $handle;

    /**
     * Constructor.
     *
     * @param resource $handle  File handler.
     */
    public function __construct($handle)
    {
        $this->handle = $handle;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        if (isset($this->handle) && is_resource($this->handle)) {
            fclose($this->handle);
        }
    }

    // API

    /**
     * Seek on a file pointer.
     *
     * @param integer $offset
     * @param string  $whence
     * @return integer 0 on success, -1 on failure
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        return fseek($this->handle, $offset, $whence);
    }
}
