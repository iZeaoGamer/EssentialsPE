<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\PowerTool;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PowerTool extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "powertool", "Toggle PowerTool on the item you're holding", "<command|c:chat macro> <arguments...>", false, ["pt"]);
        $this->setPermission("essentials.powertool.use");
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
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $item = $sender->getInventory()->getItemInHand();
        if($item->getId() === Item::AIR){
            $sender->sendMessage(TextFormat::RED . "[Error] §2You can't assign a command to an empty hand, silly.");
            return false;
        }

        if(count($args) === 0){
            if(!$this->getAPI()->getPowerToolItemCommand($sender, $item) && !$this->getAPI()->getPowerToolItemCommands($sender, $item) && !$this->getAPI()->getPowerToolItemChatMacro($sender, $item)){
                $this->sendUsage($sender, $alias);
                return false;
            }
            if($this->getAPI()->getPowerToolItemCommand($sender, $item) !== false){
                $sender->sendMessage(TextFormat::GREEN . "§dCommand removed from this item succesfully.");
            }elseif($this->getAPI()->getPowerToolItemCommands($sender, $item) !== false){
                $sender->sendMessage(TextFormat::GREEN . "§dCommands removed from this item succesfully.");
            }
            if($this->getAPI()->getPowerToolItemChatMacro($sender, $item) !== false){
                $sender->sendMessage(TextFormat::GREEN . "§dChat macro removed from this item succesfully.");
            }
            $this->getAPI()->disablePowerToolItem($sender, $item);
        }else{
            if($args[0] === "pt" || $args[0] === "ptt" || $args[0] === "powertool" || $args[0] === "powertooltoggle"){
                $sender->sendMessage(TextFormat::RED . "[Error] §2This command can't be assigned");
                return false;
            }
            $command = implode(" ", $args);
            if(stripos($command, "c:") !== false){ //Create a chat macro
                $c = substr($command, 2);
                $this->getAPI()->setPowerToolItemChatMacro($sender, $item, $c);
                $sender->sendMessage(TextFormat::GREEN . "§dChat macro successfully assigned to this item!");
            }elseif(stripos($command, "a:") !== false){
                if(!$sender->hasPermission("essentials.powertool.append")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $commands = substr($command, 2);
                $commands = explode(";", $commands);
                $this->getAPI()->setPowerToolItemCommands($sender, $item, $commands);
                $sender->sendMessage(TextFormat::GREEN . "§dCommands successfully assigned to this item!");
            }elseif(stripos($command, "r:") !== false){
                if(!$sender->hasPermission("essentials.powertool.append")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $command = substr($command, 2);
                $this->getAPI()->removePowerToolItemCommand($sender, $item, $command);
                $sender->sendMessage(TextFormat::YELLOW . "§dCommand successfully removed from this item!");
            }elseif(count($args) === 1 && (($a = strtolower($args[0])) === "l" || $a === "d")){
                switch($a){
                    case "l":
                        $commands = false;
                        if($this->getAPI()->getPowerToolItemCommand($sender, $item) !== false){
                            $commands = $this->getAPI()->getPowerToolItemCommand($sender, $item);
                        }elseif($this->getAPI()->getPowerToolItemCommands($sender, $item) !== false){
                            $commands = $this->getAPI()->getPowerToolItemCommand($sender, $item);
                        }
                        $list = "=== Command ===";
                        if($commands === false){
                            $list .= "\n" . TextFormat::ITALIC . "**There aren't any commands for this item**";
                        }else{
                            if(!is_array($commands)){
                                $list .= "\n* /$commands";
                            }else{
                                foreach($commands as $c){
                                    $list .= "\n* /$c";
                                }
                            }
                        }
                        $chat_macro = $this->getAPI()->getPowerToolItemChatMacro($sender, $item);
                        $list .= "\n=== Chat Macro ===";
                        if($chat_macro === false){
                            $list .= "\n" . TextFormat::ITALIC . "**There aren't any chat macros for this item**";
                        }else{
                            $list .= "\n$chat_macro";
                        }
                        $list .= "\n=== End of the lists ===";
                        $sender->sendMessage($list);
                        return true;
                        break;
                    case "d":
                        if(!$this->getAPI()->getPowerToolItemCommand($sender, $item)){
                            $this->sendUsage($sender, $alias);
                            return false;
                        }
                        $this->getAPI()->disablePowerToolItem($sender, $item);
                        $sender->sendMessage(TextFormat::GREEN . "§dCommand removed from this item succesfully.");
                        return true;
                        break;
                }
            }else{
                $this->getAPI()->setPowerToolItemCommand($sender, $item, $command);
                $sender->sendMessage(TextFormat::GREEN . "§dCommand successfully assigned to this item!");
            }
        }
        return true;
    }
} 
