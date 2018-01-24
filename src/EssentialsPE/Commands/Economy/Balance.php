<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Balance extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "balance", "See how many money do you have", "[player]", false, ["bal", "money"]);
        $this->setPermission("essentials.balance.use");
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("essentials.balance.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!$player = $this->getAPI()->getPlayer($args[0])){
                $sender->sendMessage(TextFormat::RED . "[Error] §2Player not found");
                return false;
            }
        }
        $sender->sendMessage(TextFormat::AQUA . ($player === $sender ? "§aYour current balance is§b " : $player->getDisplayName() . TextFormat::GREEN . " §ahas ") . TextFormat::LIGHT_BLUE . $this->getAPI()->getCurrencySymbol() . $this->getAPI()->getPlayerBalance($player));
        return true;
    }
}