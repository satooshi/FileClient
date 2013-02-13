<?php
namespace Contrib\Component\File\Client;

abstract class AbstractFileLineClient extends AbstractFileClient
{
    protected $lineHandler;

    public function getFile()
    {
        return $this->lineHandler->getFile();
    }
}
