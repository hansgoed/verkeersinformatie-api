<?php

namespace App\Entity\Event;

use Doctrine\ORM\Mapping as ORM;

/**
 * A traffic jam.
 *
 * @ORM\Entity(repositoryClass="App\Repository\TrafficJamRepository")
 */
class TrafficJam extends AbstractEvent
{
}