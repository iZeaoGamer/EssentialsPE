<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Nick extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "nick", "Change your in-game name", "<new nick|off> [player]", true, ["nickname"]);
        $this->setPermission("essentials.nick.use");
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
        if((!isset($args[1]) && !$sender instanceof Player) || (count($args) < 1 || count($args) > 2)){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $nick = ($n = strtolower($alias[0])) === "off" || $n === "remove" || (bool) $n === false ? false : $args[0];
        $player = $sender;
        if(isset($args[1])){
            if(!$sender->hasPermission("essentials.nick.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[1]))){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                return false;
            }
        }
        if(!$nick){
            $this->getAPI()->removeNick($player);
        }else{
            if(!$this->getAPI()->setNick($player, $nick)){
                $sender->sendMessage(TextFormat::RED . "[Error] You don't have permissions to give 'colored' nicknames");
            }
        }
        $player->sendMessage(TextFormat::GREEN . "Your nick " . ($m = !$nick ? "has been removed" : "is now " . TextFormat::RESET . $nick));
        if($player !== $sender){
            $sender->sendMessage(TextFormat::GREEN . $player->getName() . (substr($player->getName(), -1, 1) === "s" ? "'" : "'s") . " nick " . $m);
        }
        return true;
    }
}
