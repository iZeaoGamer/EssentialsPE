<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Home;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Home extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "home", "Teleport to your home", "<name>", false, ["homes"]);
        $this->setPermission("essentials.home.use");
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
        if(!$sender instanceof Player || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(count($args) === 0){
            if(($list = $this->getAPI()->homesList($sender, false)) === false){
                $sender->sendMessage(TextFormat::AQUA . "§c[Error] §2You don't have any home yet.");
                return false;
            }
            $sender->sendMessage(TextFormat::AQUA . "§bHere's a list of homes:\n§a" . $list);
            return true;
        }
        if(!($home = $this->getAPI()->getHome($sender, $args[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] §2Home doesn't exists or the world is not available");
            return false;
        }
        $sender->teleport($home);
        $sender->sendMessage(TextFormat::GREEN . "§dTeleporting to home §5" . TextFormat::DARK_PURPLE . $home->getName() . TextFormat::GREEN . " §dsuccesfully!");
        return true;
    }
} 
