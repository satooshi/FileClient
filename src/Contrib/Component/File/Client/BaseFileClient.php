<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\File\File;

abstract class BaseFileClient extends AbstractFileClient
{
    /**
     * @var File
     */
    protected $file;

    /**
     * Constructor.
     *
     * @param File  $file    File.
     * @param array $options Options.
     */
    public function __construct(File $file, array $options = array())
    {
        parent::__construct($options);

        $this->file = $file;
    }

    /**
     * Return file.
     *
     * @return \Contrib\Component\File\File
     */
    public function getFile()
    {
        return $this->file;
    }
}
