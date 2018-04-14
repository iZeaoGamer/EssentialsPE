<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\ArmorInventory;
use pocketmine\utils\TextFormat;

class Repair extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "repair", "Repair items in your inventory", "[all|hand]", false, ["fix"]);
        $this->setPermission("essentials.repair.use");
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
        $a = "hand";
        if(isset($args[0])) {
            $a = strtolower($args[0]);
        }
        if(!($a === "hand" || $a === "all")){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if($a === "all"){
            if(!$sender->hasPermission("essentials.repair.all")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }
            foreach($sender->getInventory()->getContents() as $item){
                if($this->getAPI()->isRepairable($item)){
                    $item->setItem()->setDamage(0);
                }
            }
            $m = TextFormat::GREEN . "§bAll the tools in your inventory were repaired!";
            if($sender->hasPermission("essentials.repair.armor")){
                foreach($sender->getArmorInventory()->getContents() as $item){
                    if($this->getAPI()->isRepairable($item)){
                        $item->setItem()->setDamage(0);
                    }
                }
                $m .= TextFormat::AQUA . " §3(Including the equipped Armor)";
            }
        }else{
            if(!$this->getAPI()->isRepairable($sender->getInventory()->getItemInHand())){
                $sender->sendMessage(TextFormat::RED . "[Error] §2This item can't be repaired!");
                return false;
            }
            $sender->getInventory()->getItemInHand()->setItem($item)->setDamage(0);
            $m = TextFormat::GREEN . "§bItem successfully repaired!";
        }
        $sender->sendMessage($m);
        return true;
    }
}
