<?php
namespace Contrib\Component\File\Client;

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
    public function __construct(array $options = array())
    {
        $this->options = $options + static::getDefaultOptions();

        ini_set('auto_detect_line_endings', $this->options['autoDetectLineEnding']);
    }

    // internal method

    /**
     * Trim line.
     *
     * @param string $line Triming string.
     * @return string
     */
    protected function trimLine($line)
    {
        return trim(mb_convert_encoding($line, $this->options['toEncoding'], $this->options['fromEncoding']));
    }

    // accessor

    /**
     * Set options.
     *
     * @param array $options Options.
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->options = $options + $this->getDefaultOptions();
    }

    /**
     * Return options.
     *
     * @return array Options.
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Return defualt options.
     *
     * @return array Default options.
     */
    public static function getDefaultOptions()
    {
        return array(
            'newLine'              => PHP_EOL,
            'throwException'       => true,
            'autoDetectLineEnding' => true,
            'convertEncoding'      => true,
            'toEncoding'           => 'UTF-8',
            'fromEncoding'         => 'auto',
        );
    }
}
