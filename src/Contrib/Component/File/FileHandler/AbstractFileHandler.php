<?php
namespace Contrib\Component\File\FileHandler;

/**
 * Abstract File handler
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
        if (isset($this->handle)) {
            fclose($this->handle);
        }
    }

    // accessor

    /**
     * Return file handle.
     *
     * @return resource
     */
    public function getHandle()
    {
        return $this->handle;
    }
}
