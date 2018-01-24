<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;

class Sudo extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "sudo", "Run a command as another player", "<player> <command line|c:<chat message>");
        $this->setPermission("essentials.sudo.use");
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
        if(!($player = $this->getAPI()->getPlayer(array_shift($args)))){
            $sender->sendMessage(TextFormat::RED . "[Error] §2Player not found");
            return false;
        }elseif($player->hasPermission("essentials.sudo.exempt")){
            $sender->sendMessage(TextFormat::RED . "[Error] " . $player->getName() . " §2cannot be sudo'ed because they either have the permission: essentials.sudo.exempt or because they're OP.");
            return false;
        }

        $v = implode(" ", $args);
        if(substr($v, 0, 2) === "c:"){
            $sender->sendMessage(TextFormat::GREEN . "§dSending message as §5" .  $player->getDisplayName());
            $this->getAPI()->getServer()->getPluginManager()->callEvent($ev = new PlayerChatEvent($player, substr($v, 2)));
            if(!$ev->isCancelled()){
                $this->getAPI()->getServer()->broadcastMessage($this->getAPI()->getServer()->getLanguage()->translateString($ev->getFormat(), [$ev->getPlayer()->getDisplayName(), $ev->getMessage()]), $ev->getRecipients());
            }
        }else{
            $sender->sendMessage(TextFormat::AQUA . "§dCommand ran as §5" .  $player->getDisplayName());
            $this->getAPI()->getServer()->dispatchCommand($player, $v);
        }
        return true;
    }
} 
