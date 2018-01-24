<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SetSpawn extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "setspawn", "Change your server main spawn point", "", false);
        $this->setPermission("essentials.setspawn");
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
        if(!$sender instanceof Player || count($args) !== 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $sender->getLevel()->setSpawnLocation($sender);
        $sender->getServer()->setDefaultLevel($sender->getLevel());
        $sender->sendMessage(TextFormat::YELLOW . "§dSet the main spawn succesfully.");
        $this->getAPI()->getServer()->getLogger()->info(TextFormat::YELLOW . "§dServer's spawn point set to " . TextFormat::DARK_PURPLE . $sender->getLevel()->getName() . TextFormat::DARK_AQUA . " §dby " . TextFormat::DARK_PURPLE . $sender->getName());
        return true;
    }
}
