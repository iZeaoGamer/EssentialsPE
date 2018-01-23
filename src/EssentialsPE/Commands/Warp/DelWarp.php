<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Warp;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class DelWarp extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "delwarp", "Delete a warp", "<name>", true, ["remwarp", "removewarp", "closewarp"]);
        $this->setPermission("essentials.delwarp");
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
        if(count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!$this->getAPI()->warpExists($args[0])){
            $sender->sendMessage(TextFormat::RED . "[Error] Warp doesn't exists");
            return false;
        }
        if(!$sender->hasPermission("essentials.warp.override.*") && !$sender->hasPermission("essentials.warp.override.$args[0]")){
            $sender->sendMessage(TextFormat::RED . "[Error] You can't delete this warp");
            return false;
        }
        $this->getAPI()->removeWarp($args[0]);
        $sender->sendMessage(TextFormat::GREEN . "Warp successfully removed!");
        return true;
    }
} 