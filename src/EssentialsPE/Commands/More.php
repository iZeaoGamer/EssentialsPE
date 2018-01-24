<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class More extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "more", "Get a stack of the item you're holding", "", false);
        $this->setPermission("essentials.more");
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
        if(($gm = $sender->getGamemode()) === Player::CREATIVE || $gm === Player::SPECTATOR){
            $sender->sendMessage(TextFormat::RED . "[Error] §2You're in §3" . $this->getAPI()->getServer()->getGamemodeString($gm) . " §2mode");
            return false;
        }
        $item = $sender->getInventory()->getItemInHand();
        if($item->getId() === Item::AIR){
            $sender->sendMessage(TextFormat::RED . "[Error] §2You can't get a stack of AIR");
            return false;
        }
        $item->setCount($sender->hasPermission("essentials.oversizedstacks") ? $this->getPlugin()->getConfig()->get("oversized-stacks") : $item->getMaxStackSize());
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage(TextFormat::AQUA . "§dFilled up the item stack to §5" . $item->getCount());
        return true;
    }
}
