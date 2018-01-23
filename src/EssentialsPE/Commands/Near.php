<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Near extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "near", "List the players near to you", "[player]", true, ["nearby"]);
        $this->setPermission("essentials.near.use");
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
        if((!isset($args[0]) || !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("essentials.near.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                return false;
            }
        }
        $who = $player === $sender ? "you" : $player->getDisplayName();
        if(count($near = $this->getAPI()->getNearPlayers($player)) < 1){
            $m = TextFormat::GRAY . "** There are no players near to " . $who . TextFormat::GRAY . "! **";
        }else{
            $m = TextFormat::YELLOW . "** There " . (count($near) > 1 ? "are " : "is ") . TextFormat::AQUA . count($near) . TextFormat::YELLOW . "player" . (count($near) > 1 ? "s " : " ") . "near to " . $who . TextFormat::YELLOW . ":";
            foreach($near as $p){
                $m .= TextFormat::YELLOW . "\n* " . TextFormat::RESET . $p->getDisplayName();
            }
        }
        $sender->sendMessage($m);
        return true;
    }
} 