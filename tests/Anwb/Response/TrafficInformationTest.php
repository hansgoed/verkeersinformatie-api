<?php

namespace App\Tests\Anwb\Response;

use App\Anwb\Response\AbstractEvent;
use App\Anwb\Response\Events\RoadworksEvent;
use App\Anwb\Response\Events\TrafficJamEvent;
use App\Anwb\Response\EventsCollection;
use App\Anwb\Response\Location;
use App\Anwb\Response\RoadEntry;
use App\Anwb\Response\TrafficInformation;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Tests for {@see TrafficInformation}.
 */
class TrafficInformationTest extends TestCase
{
    /**
     * Test that a JSON response can be converted to a {@see TrafficInformation} object using the {@see Serializer}.
     */
    public function testDeserialize(): void
    {
        $phpDocExtractor = new PhpDocExtractor();
        $normalizer = new ObjectNormalizer(null, null, null, $phpDocExtractor);

        $serializer = new Serializer(
            [
                $normalizer,
                new ArrayDenormalizer(),
                new DateTimeNormalizer(),
            ],
            [
                new JsonEncoder(),
            ]
        );

        $realResponse = file_get_contents('tests/Anwb/stripped_down_response.json');
        $deserializedResponse = $serializer->deserialize($realResponse, TrafficInformation::class, 'json');

        $this->assertEquals(
            new TrafficInformation([
                new RoadEntry(
                    'Road with 2 traffic jams, 2 roadworks & 2 radars !!!TODO no radars right now..!!!',
                    'aWegen',
                    new EventsCollection(
                        [
                            new TrafficJamEvent(
                                '237387638',
                                'knp. Zaandam naar de A8 richting Beverwijk',
                                new Location(
                                    '52.45778',
                                    '4.83863'
                                ),
                                'knp. Zaandam naar de A8 richting Beverwijk',
                                new Location(
                                    '52.45778',
                                    '4.83863'
                                ),
                                'A7 Hoorn richting Zaanstad',
                                'Hoorn',
                                'Zaanstad',
                                'Wegwerkzaamheden. Verbindingsweg dicht.',
                                'Bij knp. Zaandam naar de A8 richting Beverwijk. Wegwerkzaamheden. Verbindingsweg dicht.',
                            ),
                            new TrafficJamEvent(
                                '237387640',
                                'knp. Prins Clausplein naar de A4 richting Amsterdam',
                                new Location(
                                    '52.06418',
                                    '4.36734'
                                ),
                                'knp. Prins Clausplein naar de A4 richting Amsterdam',
                                new Location(
                                    '52.06418',
                                    '4.36734'
                                ),
                                'A12 Den Haag richting Utrecht',
                                'Den Haag',
                                'Utrecht',
                                'Wegwerkzaamheden. Verbindingsweg dicht. Het verkeer wordt geadviseerd een andere route te kiezen',
                                'Bij knp. Prins Clausplein naar de A4 richting Amsterdam. Wegwerkzaamheden. Verbindingsweg dicht. Het verkeer wordt geadviseerd een andere route te kiezen',
                            )
                        ],
                        [
                            new RoadworksEvent(
                                '237347015',
                                'Deventer-Oost',
                                new Location(
                                    '52.23694',
                                    '6.21645'
                                ),
                                'Deventer-Oost',
                                new Location(
                                    '52.23694',
                                    '6.21645'
                                ),
                                'A1 Apeldoorn richting Hengelo',
                                'Apeldoorn',
                                'Hengelo',
                                'De afrit is dicht. Van 20 April 2020 22:00 uur tot 22 April 2020 06:00 uur.',
                                'Bij Deventer-Oost. De afrit is dicht. Van 20 April 2020 22:00 uur tot 22 April 2020 06:00 uur.',
                                new DateTime('2020-04-20T20:00:00'),
                                new DateTime('2020-04-22T04:00:00')
                            ),
                            new RoadworksEvent(
                                '237373006',
                                'Muiden',
                                new Location(
                                    '52.32795',
                                    '5.0486'
                                ),
                                'Muiden',
                                new Location(
                                    '52.32795',
                                    '5.0486'
                                ),
                                'A1 Amsterdam richting Amersfoort',
                                'Amsterdam',
                                'Amersfoort',
                                'Oprit dicht. Van 20 April 2020 23:00 uur tot 21 April 2020 05:00 uur.',
                                'Bij Muiden. Oprit dicht. Van 20 April 2020 23:00 uur tot 21 April 2020 05:00 uur.',
                                new DateTime('2020-04-20T21:00:00'),
                                new DateTime('2020-04-21T03:00:00')
                            )
                        ],
                        [
                            // TODO I don't know what a radar looks like yet, as there aren't any right now.
                        ]
                    )
                ),
                new RoadEntry(
                    'Road with 1 traffic jam, 1 roadwork & 1 radar !!!TODO no radars right now..!!!',
                    'aWegen',
                    new EventsCollection(
                        [
                            new TrafficJamEvent(
                                '237387638',
                                'knp. Zaandam naar de A8 richting Beverwijk',
                                new Location(
                                    '52.45778',
                                    '4.83863'
                                ),
                                'knp. Zaandam naar de A8 richting Beverwijk',
                                new Location(
                                    '52.45778',
                                    '4.83863'
                                ),
                                'A7 Hoorn richting Zaanstad',
                                'Hoorn',
                                'Zaanstad',
                                'Wegwerkzaamheden. Verbindingsweg dicht.',
                                'Bij knp. Zaandam naar de A8 richting Beverwijk. Wegwerkzaamheden. Verbindingsweg dicht.',
                            ),
                        ],
                        [
                            new RoadworksEvent(
                                '237347015',
                                'Deventer-Oost',
                                new Location(
                                    '52.23694',
                                    '6.21645'
                                ),
                                'Deventer-Oost',
                                new Location(
                                    '52.23694',
                                    '6.21645'
                                ),
                                'A1 Apeldoorn richting Hengelo',
                                'Apeldoorn',
                                'Hengelo',
                                'De afrit is dicht. Van 20 April 2020 22:00 uur tot 22 April 2020 06:00 uur.',
                                'Bij Deventer-Oost. De afrit is dicht. Van 20 April 2020 22:00 uur tot 22 April 2020 06:00 uur.',
                                new DateTime('2020-04-20T20:00:00'),
                                new DateTime('2020-04-22T04:00:00')
                            ),
                        ],
                        [
                            // TODO I don't know what a radar looks like yet, as there aren't any right now.
                        ]
                    )
                ),
                new RoadEntry(
                    'Road with no traffic jam, no roadwork & no radar',
                    'nWegen',
                    new EventsCollection(
                        [],
                        [],
                        []
                    )
                ),
            ]),
            $deserializedResponse
        );

        $this->markTestIncomplete('Radars weren\'t present at the time of creation');
    }
}
