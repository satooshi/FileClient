<?php
namespace Contrib\Component\File\FileHandler\Ltsv;

use Contrib\Component\File\FileHandler\Plain\Reader as LineReader;
use Contrib\Component\File\Parser\LtsvParser;

/**
 * LTSV file line reader.
 */
class Reader extends LineReader
{
    /**
     * LtsvParser object.
     *
     * @var LtsvParser
     */
    protected $parser;

    /**
     * Constructor.
     *
     * @param resource $handle
     */
    public function __construct($handle)
    {
        parent::__construct($handle);

        $this->parser = new LtsvParser();
    }

    /**
     * Return file line (fgets() function wrapper).
     *
     * @param integer $length Length to read.
     * @return array LTSV items.
     */
    public function read($length = null)
    {
        $line = parent::read($length);

        return $this->parser->parseLine($line);
    }
}
