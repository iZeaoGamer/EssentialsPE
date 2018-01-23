<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Whois extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "whois", "Display player information", "<player>");
        $this->setPermission("essentials.whois");
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
        if(count($args) < 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($alias[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        $data = $this->getAPI()->getPlayerInformation($player);
        if(!$sender->hasPermission("essentials.geoip.show") || $player->hasPermission("essentials.geoip.hide")){
            unset($data["location"]);
        }
        $m = TextFormat::AQUA . "Information:\n";
        foreach($data as $k => $v){
            $m .= TextFormat::GRAY . " * " . ucfirst($k) . ": $v";
        }
        $sender->sendMessage($m);
        return true;
    }
}