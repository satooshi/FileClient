<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\File\File;

/**
 * Abstract file client.
 *
 * options:
 *
 * * newLine: string New line to be written (Default is PHP_EOL).
 * * throwException: boolean Whether to throw exception.
 * * autoDetectLineEnding: boolean Whether to use auto_detect_line_endings.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractFileClient
{
    /**
     * @var File
     */
    protected $file;

    /**
     * Line handler object.
     *
     * @var mixed
     */
    protected $lineHandler;

    /**
     * Options.
     *
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param string $path    File path.
     * @param array  $options Options.
     */
    public function __construct($path, array $options = array())
    {
        $this->options = $options + array(
            'newLine'              => PHP_EOL,
            'throwException'       => true,
            'autoDetectLineEnding' => true,
        );

        $this->file = new File($path, $this->options['throwException']);
    }

    // accessor

    /**
     * Return File object.
     *
     * @return \Contrib\Component\File\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Return line handler.
     *
     * @return mixed
     */
    public function getLineHandler()
    {
        if (isset($this->lineHandler)) {
            return $this->lineHandler;
        }

        return null;
    }

    /**
     * Return options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
