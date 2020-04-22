<?php

namespace App\Tests\Anwb\Response;

use App\Anwb\Response\AbstractEvent;
use App\Anwb\Response\Events\RadarEvent;
use App\Anwb\Response\Events\RoadworkEvent;
use App\Anwb\Response\Events\TrafficJamEvent;
use App\Anwb\Response\Location;
use App\Anwb\Response\Road;
use App\Anwb\Response\Segment;
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
            new TrafficInformation(
                [
                    new Road(
                        'Road with 2 traffic jams, 2 roadworks & 2 radars',
                        'aWegen',
                        [
                            new Segment(
                                'start',
                                'end',
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
                                        'Wegwerkzaamheden. Verbindingsweg dicht.'
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
                                        'Wegwerkzaamheden. Verbindingsweg dicht. Het verkeer wordt geadviseerd een andere route te kiezen'
                                    ),
                                ],
                                [
                                    new RoadworkEvent(
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
                                        'De afrit is dicht. Van 20 April 2020 22:00 uur tot 22 April 2020 06:00 uur.',
                                        new DateTime('2020-04-20T20:00:00Z'),
                                        new DateTime('2020-04-22T04:00:00Z')
                                    ),
                                    new RoadworkEvent(
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
                                        'Oprit dicht. Van 20 April 2020 23:00 uur tot 21 April 2020 05:00 uur.',
                                        new DateTime('2020-04-20T21:00:00Z'),
                                        new DateTime('2020-04-21T03:00:00Z')
                                    ),
                                ],
                                [
                                    new RadarEvent(
                                        '819351122',
                                        'Enschede',
                                        new Location(
                                            '52.20062',
                                            '6.88598'
                                        ),
                                        'Enschede-West',
                                        new Location(
                                            '52.20702',
                                            '6.8235'
                                        ),
                                        'Bij hectometerpaal 70.2.',
                                    ),
                                    new RadarEvent(
                                        '819351123',
                                        'Enschede',
                                        new Location(
                                            '52.20062',
                                            '6.88598'
                                        ),
                                        'Enschede-West',
                                        new Location(
                                            '52.20702',
                                            '6.8235'
                                        ),
                                        'Bij hectometerpaal 70.2.',
                                    ),
                                ]
                            ),
                        ]
                    ),
                    new Road(
                        'Road with 1 traffic jam, 1 roadwork & 1 radar divided in 2 segments',
                        'a',
                        [
                            new Segment(
                                'Almelo',
                                'Enschede',
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
                                        'Wegwerkzaamheden. Verbindingsweg dicht.',
                                    ),
                                ],
                                [
                                ],
                                [
                                    new RadarEvent(
                                        '819351122',
                                        'Enschede',
                                        new Location(
                                            '52.20062',
                                            '6.88598'
                                        ),
                                        'Enschede-West',
                                        new Location(
                                            '52.20702',
                                            '6.8235'
                                        ),
                                        'Bij hectometerpaal 70.2.',
                                    ),
                                ]
                            ),
                            new Segment(
                                'Enschede',
                                'Almelo',
                                [],
                                [
                                    new RoadworkEvent(
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
                                        'De afrit is dicht. Van 20 April 2020 22:00 uur tot 22 April 2020 06:00 uur.',
                                        new DateTime('2020-04-20T20:00:00Z'),
                                        new DateTime('2020-04-22T04:00:00Z')
                                    ),
                                ]
                            ),
                        ]
                    ),
                    new Road(
                        'Road with no traffic jam, no roadwork & no radar',
                        'nWegen',
                        [
                            new Segment(
                                'Roermond',
                                'Etsberg',
                                [],
                                [],
                                []
                            ),
                        ]
                ),
            ]),
            $deserializedResponse
        );
    }
}
