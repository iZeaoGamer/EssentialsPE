<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\level\particle\HeartParticle;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Heal extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "heal", "Heal yourself or other player", "[player]");
        $this->setPermission("essentials.heal.use");
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
        if(isset($args[0]) && !($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        if($player->getName() !== $sender->getName() && !$sender->hasPermission("essentials.heal.other")) {
        	$sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
        	return false;
        }
        $player->heal(new EntityRegainHealthEvent($player, $player->getMaxHealth() - $player->getHealth(), EntityRegainHealthEvent::CAUSE_CUSTOM));
        $player->getLevel()->addParticle(new HeartParticle($player->add(0, 2), 4));
        $player->sendMessage(TextFormat::GREEN . "You have been healed!");
        if($player !== $sender){
            $sender->sendMessage(TextFormat::GREEN . $player->getDisplayName() . " has been healed!");
        }
        return true;
    }
}
