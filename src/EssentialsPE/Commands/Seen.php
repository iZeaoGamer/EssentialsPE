<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Seen extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "seen", "See player's last played time", "<player>");
        $this->setPermission("essentials.seen");
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
        if(count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(($player = $this->getAPI()->getOfflinePlayer($args[0])) instanceof Player){
            $sender->sendMessage(TextFormat::GREEN . $player->getDisplayName() . " is online!");
            return true;
        }
        if(!is_numeric($player->getLastPlayed())){
            $sender->sendMessage(TextFormat::RED .  $args[0] . " has never played on this server.");
            return false;
        }
        /**
         * a = am/pm
         * i = Minutes
         * h = Hour (12 hours format with leading zeros)
         * l = Day name
         * j = Day number (1 - 30/31)
         * F = Month name
         * Y = Year in 4 digits (1999)
         */
        $sender->sendMessage(TextFormat::AQUA .  $player->getName() ." was last seen on " . TextFormat::RED . date("l, F j, Y", ($t = $player->getLastPlayed() / 1000)) . TextFormat::AQUA . " at " . TextFormat::RED . date("h:ia", $t));
        return true;
    }
}
