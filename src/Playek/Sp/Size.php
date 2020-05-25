<?php

namespace Playek\Sp;

use Playek\Sp\command\Command;
use Playek\Sp\data\Data;
use pocketmine\plugin\PluginBase;

class Size extends PluginBase
{

    public const CMD = "size";
    public const DEFAULT_PERMISSION = "cosmetic.size";

    public static $data;

    public function onLoad():void
    {
        self::$data = new Data($this);
        self::$data->load();
    }

    public function onDisable():void {
        if(self::$data instanceof Data){
            self::$data->save();
        }
    }

    public function onEnable():void
    {
        @mkdir($this->getDataFolder());

        $this->saveResource("data.yml");

        $this->getServer()->getCommandMap()->register(self::CMD, new Command($this));
    }
}
?>