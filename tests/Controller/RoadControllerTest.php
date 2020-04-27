<?php

namespace App\Tests\Controller;

use App\Controller\RoadController;
use App\Entity\Event\TrafficJam;
use App\Entity\Location;
use App\Entity\Road;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

class RoadControllerTest extends TestCase
{
    /**
     * @var MockObject|Serializer
     */
    private $serializerMock;

    /**
     * @var RoadController
     */
    private RoadController $roadController;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->serializerMock = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->roadController = new RoadController($this->serializerMock);
    }

    /**
     * Test that {@see RoadController::show} serializes the road and returns it as a JsonResponse.
     *
     * @dataProvider validQueryParametersProvider
     */
    public function testShow(array $queryParameters)
    {
        $road = new Road('A2');

        $this->serializerMock->expects($this->once())
            ->method('normalize')
            ->with(
                $road
            )
            ->willReturn(['asdf']);

        $request = new Request($queryParameters);

        $expectedResponse = new JsonResponse(
            ['asdf'],
            200,
            [
                'Access-Control-Allow-Origin' => '*'
            ]
        );

        $response = $this->roadController->show($road, $request);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * Test that {@see RoadController::showTrafficJams} fetches traffic jams from road object, serializes them and
     * returns them.
     *
     * @dataProvider validQueryParametersProvider
     */
    public function testShowTrafficJams(array $queryParameters) {
        /** @var Road|MockObject $roadMock */
        $roadMock = $this->getMockBuilder(Road::class)
            ->disableOriginalConstructor()
            ->getMock();

        $trafficJams = new ArrayCollection([
            'a',
            'b',
        ]);

        $roadMock->expects($this->once())
            ->method('getTrafficJams')
            ->willReturn($trafficJams);

        $normalizedTrafficJams = ['hi' => 'there'];
        $this->serializerMock->expects($this->once())
            ->method('normalize')
            ->with(
                $trafficJams,
                null,
                [
                    'groups' => [
                        'event_context',
                    ],
                ]
            )
            ->willReturn($normalizedTrafficJams);

        $request = new Request($queryParameters);
        $response = $this->roadController->showTrafficJams($roadMock, $request);

        $expectedResponse = new JsonResponse(
            $normalizedTrafficJams,
            200,
            [
                'Access-Control-Allow-Origin' => '*'
            ]
        );

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * DataProvider that provides parameter arrays with query parameters. They should all be valid.
     *
     * @return string[]
     */
    public function validQueryParametersProvider()
    {
        return [
            'empty bag' => [[]],
            'valid datetime' => [
                [
                    'datetime' => '2020-04-27T12:46:23.000-04:00'
                ]
            ],
        ];
    }
}
