<?php

namespace App\Tests\Entity;

use App\Entity\Event\EventInterface;
use App\Entity\Road;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Tests for {@see Road}.
 */
class RoadTest extends TestCase
{
    /**
     * Test that constructor arguments are correctly applied to their properties.
     */
    public function test__construct()
    {
        $road = new Road('A323');

        $this->assertEquals('A323', $road->getName());
    }

    /**
     * Test that {@see Road::getTrafficJams} filters its return value to include only actual traffic jams.
     *
     * @dataProvider eventsToFilterProvider
     */
    public function testGetTrafficJams(Collection $trafficJams, Collection $filteredTrafficJams)
    {
        $road = new Road('dsffd');

        $reflectionClass = new \ReflectionClass($road);
        $trafficJamsProperty = $reflectionClass->getProperty('trafficJams');
        $trafficJamsProperty->setAccessible(true);
        $trafficJamsProperty->setValue($road, $trafficJams);

        $road->setDateTimeFilter(new DateTime());

        $this->assertEquals($filteredTrafficJams, $road->getTrafficJams());
    }
    
    /**
     * Test that {@see Road::getRoadworks} filters its return value to include only actual roadworks.
     *
     * @dataProvider eventsToFilterProvider
     */
    public function testGetRoadworks(Collection $roadworks, Collection $filteredRoadworks)
    {
        $road = new Road('dsffd');

        $reflectionClass = new \ReflectionClass($road);
        $roadworksProperty = $reflectionClass->getProperty('roadworks');
        $roadworksProperty->setAccessible(true);
        $roadworksProperty->setValue($road, $roadworks);

        $road->setDateTimeFilter(new DateTime());

        $this->assertEquals($filteredRoadworks, $road->getRoadworks());
    }

    /**
     * DataProvider that provides a list with all events linked to a road and the events that should be returned by the
     * getter for the event.
     *
     * @return Collection[][]
     */
    public function eventsToFilterProvider()
    {
        $actualEvent1 = $this->createEventMock(true);
        $actualEvent2 = $this->createEventMock(true);

        $notActualEvent1 = $this->createEventMock(false);
        $notActualEvent2 = $this->createEventMock(false);

        return [
            'all actual' => [
                new ArrayCollection([
                    $actualEvent1,
                    $actualEvent2,
                ]),
                new ArrayCollection([
                    $actualEvent1,
                    $actualEvent2,
                ])
            ],
            'none' => [
                new ArrayCollection([]),
                new ArrayCollection([]),
            ],
            'all not actual' => [
                new ArrayCollection([
                    $notActualEvent1,
                    $notActualEvent2,
                ]),
                new ArrayCollection([])
            ],
            'one actual, one not' => [
                new ArrayCollection([
                    $actualEvent1,
                    $notActualEvent1,
                ]),
                new ArrayCollection([
                    $actualEvent1,
                ])
            ]
        ];
    }

    /**
     * Create an {@see EventInterface} mock with only the {@see EventInterface::isActual} method filled.
     *
     * @return MockObject|EventInterface
     */
    private function createEventMock(bool $actual) {
        $eventMock = $this->getMockBuilder(EventInterface::class)->getMock();

        $eventMock->method('isActual')->willReturn($actual);

        return $eventMock;
    }

    /**
     * Test that {@see Road::setDateTimeFilter} is mapped properly.
     */
    public function testSetDateTimeFilter()
    {
        $road = new Road('');
        $date = new DateTime();

        $road->setDateTimeFilter($date);

        $reflectionClass = new \ReflectionClass($road);
        $roadworksProperty = $reflectionClass->getProperty('dateTimeFilter');
        $roadworksProperty->setAccessible(true);

        $value = $roadworksProperty->getValue($road);

        $this->assertSame($date, $value);
    }
}
