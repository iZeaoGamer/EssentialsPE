<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TempBan extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tempban", "Temporarily bans the specified player", "<player> <time...> [reason ...]");
        $this->setPermission("essentials.tempban");
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
        if(count($args) < 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $name = array_shift($args);
        if(!($info = $this->getAPI()->stringToTimestamp(implode(" ", $args)))){
            $sender->sendMessage(TextFormat::RED . "[Error] §2Please specify a valid time");
            return false;
        }
        /** @var \DateTime $date */
        $date = $info[0];
        $reason = $info[1];
        if(($player = $this->getAPI()->getPlayer($name)) instanceof Player){
            if($player->hasPermission("essentials.ban.exempt")){
                $sender->sendMessage(TextFormat::RED . "[Error] " . $player->getDisplayName() . " §2can't be banned because they either have the permission: essentials.ban.exempt or because they're OP.");
                return false;
            }else{
                $player->kick(TextFormat::RED . "§aBanned until " . TextFormat::AQUA . $date->format("l, F j, Y") . TextFormat::DARK_PURPLE . " at " . TextFormat::DARK_AQUA . $date->format("h:ia") . (trim($reason) !== "" ? TextFormat::YELLOW . "§d\nReason: " . TextFormat::DARK_PURPLE . $reason : ""), false);
            }
        }
        $sender->getServer()->getNameBans()->addBan(($player instanceof Player ? $player->getName() : $name), (trim($reason) !== "" ? $reason : null), $date, "essentialspe");
        Command::broadcastCommandMessage($sender, "§dBanned player §5" . ($player instanceof Player ? $player->getName() : $name) . " §auntil §3" . $date->format("l, F j, Y") . " §bat §3" . $date->format("h:ia") . (trim($reason) !== "" ? TextFormat::YELLOW . " §dReason: " . TextFormat::DARK_PURPLE . $reason : ""));
        return true;
    }
}
