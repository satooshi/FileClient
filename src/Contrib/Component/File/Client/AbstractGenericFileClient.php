<?php
namespace Contrib\Component\File\Client;

use Symfony\Component\Serializer\Serializer;

/**
 * Abstract generic file client.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractGenericFileClient extends AbstractFileClient
{
    protected $fileClient;

    /**
     * @var Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    /**
     * File format.
     *
     * @var string
     */
    protected $format;
}
