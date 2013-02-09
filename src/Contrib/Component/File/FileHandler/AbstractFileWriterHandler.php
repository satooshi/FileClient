<?php
namespace Contrib\Component\File\FileHandler;

/**
 * Abstract file writer handler.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractFileWriterHandler extends AbstractFileHandler
{
    /**
     * New line to be written (Default is PHP_EOL).
     *
     * @var string
     */
    protected $newLine;

    /**
     * Constructor.
     *
     * @param resource $handle  File handler.
     * @param string   $newLine New line to be written (Default is PHP_EOL).
     */
    public function __construct($handle, $newLine = PHP_EOL)
    {
        parent::__construct($handle);

        $this->newLine = $newLine;
    }

    // internal method

    /**
     * Return string appended new line.
     *
     * @param string $str
     * @return string
     */
    protected function newLine($str)
    {
        return $str . $this->newLine;
    }
}
