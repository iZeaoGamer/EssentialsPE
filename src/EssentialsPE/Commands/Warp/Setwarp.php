<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Warp;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Setwarp extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "setwarp", "Create a warp (or update it)", "<name>", false, ["openwarp", "createwarp"]);
        $this->setPermission("essentials.setwarp");
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
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(($existed = $this->getAPI()->warpExists($args[0])) && !$sender->hasPermission("essentials.warp.override.*") && !$sender->hasPermission("essentials.warp.override.$args[0]")){
            $sender->sendMessage(TextFormat::RED . "[Error] You can't modify this warp position");
            return false;
        }
        if(!$this->getAPI()->setWarp($args[0], $sender->getPosition(), $sender->getYaw(), $sender->getPitch())){
            $sender->sendMessage(TextFormat::RED . "Invalid warp name given! Please be sure to only use alphanumerical characters and underscores");
            return false;
        }
        $sender->sendMessage(TextFormat::GREEN . "Warp successfully " . ($existed ? "updated!" : "created!"));
        return true;
    }
} 