<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Depth extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "depth", "Display your depth related to sea-level", "", false, ["height"]);
        $this->setPermission("essentials.depth");
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
        $sender->sendMessage(TextFormat::AQUA . "§aYou're§b " . (($pos = $sender->getFloorY() - 63) === 0 ? "at" : (abs($pos) . " §ameters " . ($pos > 0 ? "§babove" : "§3elow"))) . " §athe sea level.");
        return true;
    }
}