<?php

namespace App\Tests\Controller;

use App\Controller\EventController;
use App\Repository\AbstractEventRepository;
use App\Repository\RoadworkRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

/**
 * Tests for {@see EventController}
 */
class EventControllerTest extends TestCase
{
    /**
     * @var MockObject|Serializer
     */
    private MockObject $serializerMock;

    /**
     * @var EventController
     */
    private EventController $eventController;

    public function setUp()
    {
        $this->serializerMock = $this->getMockBuilder(Serializer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventController = new EventController($this->serializerMock);
    }

    /**
     * Test that showEvents fetches Events and returns them.
     *
     * @dataProvider ShowEventMethodNamesProvider
     */
    public function testShowXEventsFetchesXEventsAndReturnsThem($methodName)
    {
        /**
         * @var AbstractEventRepository|MockObject $eventRepositoryMock
         */
        $eventRepositoryMock = $this->getMockBuilder(AbstractEventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var Request|MockObject $requestMock
         */
        $requestMock = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $requestMock->query = new ParameterBag();

        $fakeData = ['asdf', 'ghjk', 'l;'];
        $normalizedFakeData = ['fake' => 'data'];

        $currentDate = DateTime::createFromFormat('U', time());

        $eventRepositoryMock->expects($this->once())
            ->method('getActiveEventsForDateTime')
            ->with($currentDate)
        ->willReturn($fakeData);

        $this->serializerMock->expects($this->once())
            ->method('normalize')
            ->with(
                new ArrayCollection($fakeData),
                null,
                [
                    'groups' => [
                        'event_context'
                    ]
                ]
            )->willReturn($normalizedFakeData);

        $response = $this->eventController->$methodName($eventRepositoryMock, $requestMock);
        $this->assertEquals(
            new JsonResponse($normalizedFakeData, 200, [
                'Access-Control-Allow-Origin' => '*'
            ]),
            $response
        );
    }

    /**
     * Test that when a datetime is present in the request query the datetime is passed to the repository function to
     * fetch the actual roadworks.
     */
    public function testShowRoadworksWithDatetimeArgumentPassesDateTimeToRepositoryMethod()
    {
        /**
         * @var RoadworkRepository|MockObject $roadworkRepositoryMock
         */
        $roadworkRepositoryMock = $this->getMockBuilder(RoadworkRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var Request|MockObject $requestMock
         */
        $requestMock = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dateString = '2020-04-27T12:46:23.000-04:00';
        $requestMock->query = new ParameterBag([
            'datetime' => $dateString
        ]);

        $this->serializerMock->method('normalize')->willReturn([]);

        $expectedDateTime = new DateTime();
        $expectedDateTime->setDate(2020, 04, 27);
        $expectedDateTime->setTime(18, 46, 23, 0);
        $expectedDateTime->setTimezone(new \DateTimeZone('-04:00'));

        $roadworkRepositoryMock->expects($this->once())
            ->method('getActiveEventsForDateTime')
            ->with($expectedDateTime);

        $this->eventController->showRoadworks($roadworkRepositoryMock, $requestMock);
    }

    public function ShowEventMethodNamesProvider()
    {
        return [
            ['showRoadworks'],
            ['showTrafficJams'],
            // ['showRadars'],
        ];
    }
}
