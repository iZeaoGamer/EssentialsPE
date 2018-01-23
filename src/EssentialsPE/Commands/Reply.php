<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\RemoteConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Reply extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "reply", "Quickly reply to the last person that messaged you", "<message ...>", true, ["r"]);
        $this->setPermission("essentials.reply");
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
        if(!($t = $this->getAPI()->getQuickReply($sender))){
            $sender->sendMessage(TextFormat::RED . "[Error] No target available for QuickReply");
            return false;
        }
        if(strtolower($t) !== "console" && strtolower($t) !== "rcon"){
            if(!($t = $this->getAPI()->getPlayer($t))){
                $sender->sendMessage(TextFormat::RED . "[Error] No player available for QuickReply");
                $this->getAPI()->removeQuickReply($sender);
                return false;
            }
        }
        $sender->sendMessage(TextFormat::YELLOW . "[me -> " . ($t instanceof Player ? $t->getDisplayName() : $t) . "]" . TextFormat::RESET . " " . implode(" ", $args));
        $m = TextFormat::YELLOW . "[" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> me]" . TextFormat::RESET . " " . implode(" ", $args);
        if($t instanceof Player){
            $t->sendMessage($m);
        }else{
            $this->getPlugin()->getLogger()->info($m);
        }
        $this->getAPI()->setQuickReply(($t instanceof Player ? $t : ($t === "console" ? new ConsoleCommandSender() : new RemoteConsoleCommandSender())), $sender);
        return true;
    }
}