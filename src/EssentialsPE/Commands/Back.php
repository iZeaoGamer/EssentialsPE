<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Back extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "back", "Teleport to your previous location", "", false, ["return"]);
        $this->setPermission("essentials.back.use");
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
        if(!($pos = $this->getAPI()->getLastPlayerPosition($sender))){
            $sender->sendMessage(TextFormat::RED . "[Error] No previous position available");
            return false;
        }
        $sender->sendMessage(TextFormat::GREEN . "Teleporting...");
        $sender->teleport($pos);
        return true;
    }
} 