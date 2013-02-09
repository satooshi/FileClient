<?php
namespace Contrib\Component\File\FileHandler\Generic;

use Contrib\Component\File\FileHandler\Plain\Iterator as LineIterator;

/**
 * Generic line iterator.
 */
class Iterator extends LineIterator
{
    /**
     * Reader object.
     *
     * @var Contrib\Component\File\FileHandler\Serializer\Reader
     */
    protected $reader;

    /**
     * Constructor.
     *
     * @param Contrib\Component\File\FileHandler\Serializer\Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
}
