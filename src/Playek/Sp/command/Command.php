<?php

namespace Playek\Sp\command;

use Playek\Sp\events\PlayerChangeSize;
use Playek\Sp\Size;
use pocketmine\Player;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class Command extends PluginCommmand
{

    public function __construct(Size $main)
    {
        parent::__construct(Size::CMD, $main);
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $commandLabelm array $args):bool
    {
        if(!($sender instanceof Player))
        {
            $sender->sendMessage("§cThis command only works in-game");
            return true;
        }
        if(empty($args[0]))
        {
            $sender->sendMessage("§cUse: /".Size::CMD." help");
            return true;
        }
        switch(strtolower($args[0])) {
            case "help":
                $linesHelp = [
                    "§c/{cmd} create <nameSize> <scale> <permission>",
                    "§c/{cmd} set <size>"
                ];
                foreach ($linesHelp as $cmd) {
                    $cmd = str_replace("{cmd}", Size::CMD, $cmd);
                    $sender->sendMessage($cmd);
                }
                return true;
                break;
            case "create":
                if (empty($args[1]) or empty($args[2])) {
                    $sender->sendMessage("§cUse: /" . Size::CMD . " create <nameSize> <scale> <permission>");
                    return true;
                }
                $name = $args[1];
                $scale = (float)$args[2];
                $permission = Size::DEFAULT_PERMISSION;
                if (!empty($args[3])) {
                    $permission = $args[3];
                }
                if (Size::$data->create($name, $scale, $permission)) {
                    $sender->sendMessage("§aYou have created a new size!");
                } else {
                    $sender->sendMessage("§cThere was an error creating your size");
                }
                return true;
                break;
            case "set":
                if (empty($args[1])) {
                    $sender->sendMessage("§cUse: /size set <nameSize>");
                    return true;
                }
                if (Size::$data->exists($args[1])) {
                    $permission = Size::$data->getPermission($args[1]);
                    if ($permission === "default") {
                        $permission = str_replace("default", Size::DEFAULT_PERMISSION, $permission);
                    }
                    if ($sender->hasPermission($permission)) {
                        $ev = new PlayerChangeSize($sender, Size::$data->getScale($args[1]));
                        $ev->call();
                        if(!$ev->isCancelled()) {
                            $sender->setScale(Size::$data->getScale($args[1]));
                            $sender->sendMessage("§eYour size has been updated to " . Size::$data->getScale($args[1]));
                        }else{
                            $sender->sendMessage("§cYour size could not be changed due to canceled event");
                        }
                    }else{
                        $sender->sendMessage("§cYou do not have permission to change to this size");
                    }
                }else{
                    $sender->sendMessage("§cThis size does not exist!");
                }
                return true;
        }
    }
}
?>