<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\File\File;

/**
 * Abstract file client.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractFileClient
{
    /**
     * Options.
     *
     * * newLine: string New line to be written (Default is PHP_EOL).
     * * throwException: boolean Whether to throw exception.
     * * autoDetectLineEnding: boolean Whether to use auto_detect_line_endings.
     *
     * @var array
     */
    protected $options;

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
     * Return options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}