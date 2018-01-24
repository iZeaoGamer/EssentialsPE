<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Mute extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "mute", "Prevent a player from chatting", "<player> [time...]", true, ["silence"]);
        $this->setPermission("essentials.mute.use");
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
        if(count($args) < 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer(array_shift($args)))){
            $sender->sendMessage(TextFormat::RED . "[Error] §2Player not found.");
            return false;
        }
        if($player->hasPermission("essentials.mute.exempt") && !$this->getAPI()->isMuted($player)){
            $sender->sendMessage(TextFormat::RED . $player->getDisplayName() . " §2can't be muted because they either the permission: essentials.mute.exempt or because they're OP.");
            return false;
        }
        /** @var \DateTime $date */
        $date = null;
        if(!is_bool($info = $this->getAPI()->stringToTimestamp(implode(" ", $args)))){
            $date = $info[0];
        }
        $this->getAPI()->switchMute($player, $date, true);
        $sender->sendMessage(TextFormat::GREEN . $player->getDisplayName() . " §6has been " . ($this->getAPI()->isMuted($player) ? "§3muted " . ($date !== null ? "§6until: " . TextFormat::AQUA . $date->format("l, F j, Y") . TextFormat::RED . " §aat " . TextFormat::AQUA . $date->format("h:ia") : TextFormat::AQUA . "§5Forever" . TextFormat::YELLOW . "!") : "§dunmuted!"));
        return true;
    }
}