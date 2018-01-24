<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SetWorth extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "setworth", "Sets the worth of the item you're holding", "<worth>", false);
        $this->setPermission("essentials.setworth");
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
        }elseif(!is_numeric($args[0]) || (int) $args[0] < 0){
            $sender->sendMessage(TextFormat::RED . "[Error] §2Please provide a valid worth");
            return false;
        }elseif(($id = $sender->getInventory()->getItemInHand()->getId()) === Item::AIR){
            $sender->sendMessage(TextFormat::RED . "[Error] §2Please provide a valid item");
            return false;
        }
        $sender->sendMessage(TextFormat::YELLOW . "§5Setting worth...");
        $this->getAPI()->setItemWorth($id, (int) $args[0]);
        return true;
    }
}