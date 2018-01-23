<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\PowerTool;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PowerToolToggle extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "powertooltoggle", "Disable PowerTool from all the items", "", false, ["ptt", "pttoggle"]);
        $this->setPermission("essentials.powertooltoggle");
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
        $this->getAPI()->disablePowerTool($sender);
        $sender->sendMessage(TextFormat::YELLOW . "PowerTool disabled from all the items!");
        return true;
    }
} 