<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Pay extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "pay", "Pays a player from your balance", "<player> <amount>", false);
        $this->setPermission("essentials.pay");
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
        if(!$sender instanceof Player || count($args) !== 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        if($args[1][0] === "-"){
            $sender->sendMessage(TextFormat::RED . "[Error] You can't pay a negative value");
            return false;
        }
        $balance = $this->getAPI()->getPlayerBalance($sender);
        $newBalance = $balance - (int) $args[1];
        if($balance < $args[1] || $newBalance < $this->getAPI()->getMinBalance() || ($newBalance < 0 && !$player->hasPermission("essentials.eco.loan"))){
            $sender->sendMessage(TextFormat::RED . "[Error] You don't have enough money to pay");
            return false;
        }
        $sender->sendMessage(TextFormat::YELLOW . "Paying...");
        $this->getAPI()->setPlayerBalance($sender, $newBalance); //Take out from the payer balance.
        $this->getAPI()->addToPlayerBalance($player, (int) $args[1]); //Pay to the other player
        return true;
    }
}