<?php

namespace Playek\Sp\events;

use pocketmine\event\player\PlayerEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerChangeSize extends PlayerEvent implements Cancellable
{

    private $scale;

    public function __construct(Player $player, float $scale)
    {
        $this->player = $player;
        $this->scale = $scale;
    }

    public function getScale():float {
        return $this->scale;
    }
}