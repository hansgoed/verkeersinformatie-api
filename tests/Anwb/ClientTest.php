<?php

namespace App\Tests\Anwb;

use App\Anwb\Client;
use App\Anwb\Response\Road;
use App\Anwb\Response\Segment;
use App\Anwb\Response\TrafficInformation;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Tests for {@see Client}.
 */
class ClientTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private $serializerMock;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        $this->serializerMock = $this->getMockBuilder(SerializerInterface::class)
            ->getMock();
    }

    /**
     * Test that {@see Client::getTrafficInformation()} requests the data with the HttpClient, sends the response to the
     * serializer & returns data received from the serializer.
     */
    public function testGetTrafficInformation(): void
    {
        $realResponse = file_get_contents('tests/Anwb/stripped_down_response.json');
        $mockHttpClient = new MockHttpClient(new MockResponse($realResponse));

        $client = new Client($mockHttpClient, $this->serializerMock);

        $dummyTrafficInformation = new TrafficInformation([
            new Road(
                'a1',
                'A weg',
                [
                    new Segment(
                        'start',
                        'end',
                        [],
                        [],
                        []
                    ),
                ]
            )
        ]);

        $this->serializerMock->expects($this->once())
            ->method('deserialize')
            ->with($realResponse, TrafficInformation::class, 'json')
            ->willReturn($dummyTrafficInformation);

        $this->assertSame($dummyTrafficInformation, $client->getTrafficInformation());
    }
}
