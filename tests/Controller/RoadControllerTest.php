<?php

namespace App\Tests\Controller;

use App\Controller\RoadController;
use App\Entity\Road;
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
     */
    public function testShow()
    {
        $road = new Road('A2');

        $this->serializerMock->expects($this->once())
            ->method('normalize')
            ->with(
                $road
            )
            ->willReturn(['asdf']);

        $request = new Request([]);

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
}
