<?php
namespace Contrib\Component\File\Client;

use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Abstract generic file client.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractGenericFileClient extends AbstractFileClient implements SerializerAwareInterface
{
    /**
     * @var Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    /**
     * @var AbstractFileClient
     */
    protected $fileClient;

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Serializer\SerializerAwareInterface::setSerializer()
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Create file client.
     */
    abstract protected function createFileClient();
}
