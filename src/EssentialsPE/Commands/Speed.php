<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\entity\Effect;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Speed extends BaseCommand{

    public function __construct(BaseAPI $api){
        parent::__construct($api, "speed", "Change your speed limit", "<speed> [player]");
        $this->setPermission("essentials.speed");
    }

	/**
	 * @param CommandSender $sender
	 * @param string        $alias
	 * @param array         $args
	 *
	 * @return bool
	 */
    public function execute(CommandSender $sender, string $alias, array $args): bool{
        if($this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player || count($args) < 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!is_numeric($args[0])){
            $sender->sendMessage(TextFormat::RED . "[Error] §2Please provide a valid value");
            return false;
        }
        $player = $sender;
        if(isset($args[1]) && !($player = $this->getAPI()->getPlayer($args[1]))){
            $sender->sendMessage(TextFormat::RED . "[Error] §2Player not found");
            return false;
        }
        if((int) $args[0] === 0){
            $player->removeEffect(Effect::SPEED);
        }else{
            $effect = Effect::getEffect(Effect::SPEED);
            $effect->setAmplifier($args[0]);
            $effect->setDuration(PHP_INT_MAX);
            $player->addEffect($effect);
        }
        $sender->sendMessage(TextFormat::YELLOW . "§dSpeed amplified by " . TextFormat::DARK_PURPLE . $args[0]);
        return true;
    }
}