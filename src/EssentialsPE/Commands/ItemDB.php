<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ItemDB extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "itemdb", "Display the information attached to the item you hold", "[name|id|meta]", false, ["itemno", "durability", "dura"]);
        $this->setPermission("essentials.itemdb");
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
        $item = $sender->getInventory()->getItemInHand();
        if(!isset($args[0])){
        	$args[0] = "id";
        }
        switch(strtolower($args[0])){
            case "name":
                $m = TextFormat::AQUA . "§aThis item is named: " . TextFormat::AQUA . $item->getName();
                break;
            default:
            case "id":
                $m = TextFormat::AQUA . "§aThis item ID is: " . TextFormat::AQUA . $item->getId();
                break;
            case "durability":
            case "dura":
            case "metadata":
            case "meta":
                $m = TextFormat::AQUA . "§aThis item " . ($this->getAPI()->isRepairable($item) ? "§bhas " . TextFormat::GREEN . $item->getDamage() . TextFormat::AQUA . " §5points of damage" : "§3metadata is " . TextFormat::GREEN . $item->getDamage());
                break;
        }
        $sender->sendMessage($m);
        return true;
    }
} 