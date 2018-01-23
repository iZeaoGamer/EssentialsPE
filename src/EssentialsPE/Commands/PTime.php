<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PTime extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "ptime", "Changes the time of a player", "<time> [player]", true, ["playertime"]);
        $this->setPermission("essentials.ptime.use");
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $alias, array $args): bool{
        if(!$this->testPermission($sender)){
            return false;
        }
        if((!isset($args[0]) && !$sender instanceof Player) || (count($args) < 1 || count($args) > 2)){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $static = ($alias[0][0] === "@");
        $time = strtolower((!$static ? $args[0] : substr($args[0], 1)));
        if(!is_int($time)){
            switch($time){
                case "dawn":
                case "sunrise":
                    $time = Level::TIME_SUNRISE;
                    break;
                case "day":
                    $time = Level::TIME_DAY;
                    break;
                case "noon":
                    $time = 6000;
                    break;
                case "evening":
                case "sunset":
                    $time = Level::TIME_SUNSET;
                    break;
                case "night":
                    $time = Level::TIME_NIGHT;
                    break;
            }
        }
        $player = $sender;
        if(isset($args[1])){
            if(!$sender->hasPermission("essentials.ptime.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[1]))){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                return false;
            }
        }
        if(!$this->getAPI()->setPlayerTime($player, (int) $time)){
            $sender->sendMessage(TextFormat::RED . "Something went wrong while setting the time");
            return false;
        }
        $sender->sendMessage(TextFormat::GREEN . "Setting player time...");
        return false;
    }
}