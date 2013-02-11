<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\Plain\Iterator as LineIterator;

/**
 * Generic line iterator.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class Iterator extends LineIterator
{
    /**
     * Reader object.
     *
     * @var Contrib\Component\File\FileHandler\Generic\Reader
     */
    protected $reader;

    /**
     * Constructor.
     *
     * @param Contrib\Component\File\FileHandler\Generic\Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
}
