<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Kit extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "kit", "Get a pre-defined kit!", "[name] [player]", "[<name> <player>]", ["kits"]);
        $this->setPermission("essentials.kit.use");
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
        if(count($args) > 2){
            $this->sendUsage($sender, $alias);
            return false;
        }elseif(count($args) === 0){
            if(($list = $this->getAPI()->kitList(false)) === false){
                $sender->sendMessage(TextFormat::AQUA . "§c[Error] §2There are currently no Kits available");
                return false;
            }
            $sender->sendMessage(TextFormat::AQUA . "§aHere are a available kits:\n§b" . $list);
            return true;
        }elseif(!isset($args[1]) && !$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }elseif(!($kit = $this->getAPI()->getKit($args[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] §2Kit doesn't exist");
            return false;
        }
        switch(count($args)){
            case 1:
                if(!$sender instanceof Player){
                    $this->sendUsage($sender, $alias);
                    return false;
                }
                if(!$sender->hasPermission("essentials.kits.*") && !$sender->hasPermission("essentials.kits." . strtolower($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] §2You can't obtain this kit");
                    return false;
                }
                $kit->giveToPlayer($sender);
                $sender->sendMessage(TextFormat::GREEN . "§5Getting kit §3" . TextFormat::DARK_AQUA . $kit->getName() . "§5...");
                break;
            case 2:
                if(!$sender->hasPermission("essentials.kit.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                if(!$sender->hasPermission("essentials.kits.*") && !$sender->hasPermission("essentials.kits." . strtolower($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] §2You can't obtain this kit");
                    return false;
                }
                if(!($player = $this->getAPI()->getPlayer($args[1]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] §2Player not found");
                    return false;
                }
                $kit->giveToPlayer($player);
                $player->sendMessage(TextFormat::GREEN . "§5Getting kit " . TextFormat::DARK_AQUA . $kit->getName() . "§5...");
                $sender->sendMessage(TextFormat::GREEN . "§5Giving " . TextFormat::DARK_AQUA . $player->getDisplayName() . TextFormat::DARK_PURPLE . " §dkit " . TextFormat::DARK_AQUA . $kit->getName() . TextFormat::GREEN . "§5...");
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        $player = $sender;
        if(isset($args[1])){
            if(!$sender->hasPermission("essentials.kit.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[1]))){
                $sender->sendMessage(TextFormat::RED . "[Error] §2Player not found");
                return false;
            }
        }
        if(!$sender->hasPermission("essentials.kits.*") && !$sender->hasPermission("essentials.kits." . strtolower($args[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] §2You can't obtain this kit");
            return false;
        }
        $player->sendMessage(TextFormat::GREEN . "§5Getting kit " . TextFormat::DARK_AQUA . $kit->getName() . "§5...");
        if($player !== $sender){
            $sender->sendMessage(TextFormat::GREEN . "§5Giving " . TextFormat::DARK_AQUA . $player->getDisplayName() . TextFormat::GREEN . " §5kit " . TextFormat::DARK_AQUA . $kit->getName() . TextFormat::GREEN . "§5...");
        }
        return true;
    }
}