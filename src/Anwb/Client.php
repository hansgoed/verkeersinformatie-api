<?php

namespace App\Anwb;

use App\Anwb\Response\TrafficInformation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Client for the ANWB feed that provides traffic information.
 */
class Client
{
    private HttpClientInterface $httpClient;
    private SerializerInterface $serializer;

    public function __construct(
        HttpClientInterface $httpClient,
        SerializerInterface $serializer
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
    }

    /**
     * Get traffic information for all A and N roads.
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getTrafficInformation(): TrafficInformation
    {
        $response = $this->httpClient
            ->request(Request::METHOD_GET, 'https://api.anwb.nl/v1/incidents?apikey=QYUEE3fEcFD7SGMJ6E7QBCMzdQGqRkAi');

        /** @var TrafficInformation $trafficInformation */
        $trafficInformation = $this->serializer
            ->deserialize($response->getContent(), TrafficInformation::class, 'json');

        return $trafficInformation;
    }
}