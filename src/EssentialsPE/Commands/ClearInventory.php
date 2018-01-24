<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ClearInventory extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "clearinventory", "Clear your/other's inventory", "[player]", true, ["ci", "clean", "clearinvent"]);
        $this->setPermission("essentials.clearinventory.use");
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
        if((!isset($args[0]) &&  !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("essentials.clearinventory.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $sender->sendMessage(TextFormat::RED . "[Error] §2Player not found");
                return false;
            }
        }
        if(($gm = $player->getGamemode()) === Player::SPECTATOR){
            $sender->sendMessage(TextFormat::RED . "[Error] §3" . (isset($args[0]) ? $player->getDisplayName() . "§2is already" : "§dYou are") . " §6in §5" . $this->getAPI()->getServer()->getGamemodeString($gm) . " §6mode");
            return false;
        }
        $player->getInventory()->clearAll();
        $player->sendMessage(TextFormat::AQUA . "§dYour inventory was cleared");
        if($player !== $sender){
            $sender->sendMessage(TextFormat::DARK_PURPLE . $player->getDisplayName() . (substr($player->getDisplayName(), -1, 1) === "s" ? "'" : "'s") . " §dinventory was cleared");
        }
        return true;
    }
}
