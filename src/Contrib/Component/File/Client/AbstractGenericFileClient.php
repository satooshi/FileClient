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
    /**
     * @var Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    /**
     * Constructor.
     *
     * @param Symfony\Component\Serializer\Serializer $serializer Serializer.
     * @param array                                   $options    Options.
     */
    public function __construct(Serializer $serializer, array $options = array())
    {
        $this->serializer = $serializer;
        $this->options    = $options + static::getDefaultOptions();
    }
}
