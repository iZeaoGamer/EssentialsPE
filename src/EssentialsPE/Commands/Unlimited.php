<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Unlimited extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "unlimited", "Allow you to place unlimited blocks", "[player]", true, ["ul", "unl"]);
        $this->setPermission("essentials.unlimited.use");
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("essentials.unlimited.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                return false;
            }
        }
        if(($gm = $player->getGamemode()) === Player::CREATIVE || $gm === Player::SPECTATOR){
            $sender->sendMessage(TextFormat::RED . "[Error] " . ($player === $sender ? "you are" : $player->getDisplayName() . " is") . " in " . $this->getAPI()->getServer()->getGamemodeString($gm) . " mode");
            return false;
        }
        $this->getAPI()->switchUnlimited($player);
        $player->sendMessage(TextFormat::GREEN . "Unlimited placing of blocks " . ($s = $this->getAPI()->isUnlimitedEnabled($player) ? "enabled" : "disabled"));
        if($player !== $sender){
            $sender->sendMessage(TextFormat::GREEN . "Unlimited placing of blocks $s");
        }
        return true;
    }
} 