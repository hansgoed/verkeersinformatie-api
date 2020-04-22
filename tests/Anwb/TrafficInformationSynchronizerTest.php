<?php

namespace App\Tests\Anwb;

use App\Anwb\Client;
use App\Anwb\Response\Events\AbstractEvent;
use App\Anwb\Response\Events\RadarEvent;
use App\Anwb\Response\Events\RoadworksEvent;
use App\Anwb\Response\Events\TrafficJamEvent;
use App\Anwb\Response\EventsCollection;
use App\Anwb\Response\Location;
use App\Anwb\Response\RoadEntry;
use App\Anwb\Response\TrafficInformation;
use App\Anwb\TrafficInformationSynchronizer;
use App\Entity\AnwbEvent;
use App\Entity\Event\Roadwork;
use App\Entity\Event\TrafficJam;
use App\Entity\Road;
use App\Repository\AnwbEventRepository;
use App\Repository\RoadRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

class TrafficInformationSynchronizerTest extends TestCase
{
    /**
     * Class to test.
     */
    private TrafficInformationSynchronizer $trafficInformationSynchronizer;

    /**
     * @var MockObject|Client
     */
    private MockObject $anwbClientMock;

    /**
     * @var MockObject|AnwbEventRepository
     */
    private MockObject $anwbEventRepositoryMock;

    /**
     * @var MockObject|RoadRepository
     */
    private MockObject $roadRepositoryMock;

    /**
     * @var MockObject|EntityManagerInterface
     */
    private MockObject $entityManagerMock;

    public function setUp()
    {
        $this->anwbClientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->anwbEventRepositoryMock = $this->getMockBuilder(AnwbEventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->roadRepositoryMock = $this->getMockBuilder(RoadRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManagerMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        $this->trafficInformationSynchronizer = new TrafficInformationSynchronizer(
            $this->anwbClientMock,
            $this->anwbEventRepositoryMock,
            $this->roadRepositoryMock,
            $this->entityManagerMock
        );
    }

    public function testSynchronizeWithNewData()
    {
        // There are no events in the database yet, but this MUST be checked.
        $this->anwbEventRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        // Stop time to make timestamps match.
        ClockMock::withClockMock();
        ClockMock::register('App\Entity\Event');

        // Create data to be processed & ensure the feed is only fetched once, we don't want to spam the feed.
        $this->anwbClientMock->expects($this->once())
            ->method('getTrafficInformation')
            ->willReturn(
                new TrafficInformation(
                    [
                        new RoadEntry(
                            'A29', 'aWeg',
                            new EventsCollection(
                                [
                                    $this->createEvent('foo1', TrafficJamEvent::class),
                                    $this->createEvent('foo2', TrafficJamEvent::class),
                                ],
                                [
                                    $this->createEvent('bar1', RoadworksEvent::class),
                                    $this->createEvent('bar2', RoadworksEvent::class),
                                ],
                                [
                                    $this->createEvent('baz1', RadarEvent::class),
                                    $this->createEvent('baz2', RadarEvent::class),
                                ]
                            )
                        ),
                        new RoadEntry(
                            'N59', 'nWeg',
                            new EventsCollection(
                                [
                                    $this->createEvent('foo3', TrafficJamEvent::class),
                                ],
                                [
                                ],
                                [
                                    $this->createEvent('baz3', RadarEvent::class),
                                    $this->createEvent('baz4', RadarEvent::class),
                                ]
                            )
                        ),
                    ]
                )
            );

        // Make sure an attempt to look up the road is executed for every RoadEntry.
        $this->roadRepositoryMock->expects($this->exactly(2))
            ->method('findOneBy')
            ->withConsecutive(
                [['name' => 'A29']],
                [['name' => 'N59']]
            )->willReturn(null);

        $road1 = new Road('A29');
        $road2 = new Road('N59');

        // Make sure the correct data is persisted & everything is persisted.
        $this->anwbEventRepositoryMock->expects($this->exactly(5))
            ->method('persist')
            ->withConsecutive(
                [
                    $this->createAnwbEvent(
                        'foo1',
                        TrafficJam::class,
                        $road1
                    ),
                ],
                [
                    $this->createAnwbEvent(
                        'foo2',
                        TrafficJam::class,
                        $road1
                    ),
                ],
                [
                    $this->createAnwbEvent(
                        'bar1',
                        Roadwork::class,
                        $road1
                    ),
                ],
                [
                    $this->createAnwbEvent(
                        'bar2',
                        Roadwork::class,
                        $road1
                    ),
                ], // TODO radars aren't saved yet.
                [
                    $this->createAnwbEvent(
                        'foo3',
                        TrafficJam::class,
                        $road2
                    ),
                ],
                [
                ], // TODO radars aren't saved yet
            );

        // Make sure flush is called to get all the data into the database.
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->trafficInformationSynchronizer->synchronize();
    }

    private function createEvent(string $reference, string $eventTypeFQCN): AbstractEvent
    {
        $arguments = [
            $reference,
            'here',
            new Location(123.45, 234.56),
            'there',
            new Location(124.56, 234.56),
            'ergens halverwege',
            'vlak voor halverwege',
            'net na halverwege',
            'We hadden zin in een file',
            'Omdat het kan',
        ];

        if ($eventTypeFQCN === RoadworksEvent::class) {
            $arguments[] = new DateTimeImmutable();
            $arguments[] = new DateTimeImmutable();
        }

        return new $eventTypeFQCN(
            ...$arguments
        );
    }

    private function createAnwbEvent(
        string $reference,
        string $eventEntityTypeFQCN,
        Road $road
    ): AnwbEvent {
        return new AnwbEvent(
            $reference,
            new $eventEntityTypeFQCN(
                $road,
                new \App\Entity\Location('here', 123.45, 234.56),
                new \App\Entity\Location('there', 124.56, 234.56),
                'Omdat het kan'
            )
        );
    }

    public function testSynchronizeMarksMissingEventAsResolved()
    {
        $road1 = new Road('A29');

        /** @var MockObject|TrafficJam $eventToBeMarkedAsResolvedMock */
        $eventToBeMarkedAsResolvedMock = $this->getMockBuilder(TrafficJam::class)
            ->disableOriginalConstructor()
            ->getMock();

        $anwbEventToBeMarkedAsResolved = new AnwbEvent(
            'event-to-be-marked-as-resolved',
            $eventToBeMarkedAsResolvedMock
        );

        $this->anwbEventRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn(
                [
                    $this->createAnwbEvent(
                        'event-to-remain-the-same',
                        TrafficJam::class,
                        $road1
                    ),
                    $anwbEventToBeMarkedAsResolved,
                ]
            );

        $this->anwbClientMock->expects($this->once())
            ->method('getTrafficInformation')
            ->willReturn(
                new TrafficInformation(
                    [
                        new RoadEntry(
                            'A29', 'aWeg',
                            new EventsCollection(
                                [
                                    $this->createEvent('event-to-remain-the-same', TrafficJamEvent::class),
                                ],
                                [],
                                []
                            )
                        ),
                    ]
                )
            );

        $eventToBeMarkedAsResolvedMock->expects($this->once())
            ->method('markResolved');

        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $this->trafficInformationSynchronizer->synchronize();
    }
}
