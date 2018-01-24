<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class EssentialsPE extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "essentials", "Get the current Essentials version", "[update <check|install>]", true, ["essentials", "ess", "esspe"]);
        $this->setPermission("essentials.essentials");
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
        switch(count($args)){
            case 0:
                $sender->sendMessage(TextFormat::YELLOW . "§aYou're using§b " . TextFormat::AQUA . "§bEssentialsPE " . TextFormat::YELLOW . "§6v" . TextFormat::GOLD . $sender->getServer()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion());
                break;
            case 1:
            case 2:
                switch(strtolower($args[0])){
                    case "update":
                    case "u":
                        if(!$sender->hasPermission("essentials.update.use")){
                            $sender->sendMessage(TextFormat::DARK_RED . $this->getPermissionMessage());
                            return false;
                        }
                        if(isset($args[1]) && (($a = strtolower($args[1])) === "check" || $a === "c" || $a === "install" || $a === "i")){
                            if(!$this->getAPI()->fetchEssentialsPEUpdate($a[0] === "i")){
                                $sender->sendMessage(TextFormat::YELLOW . "§5The updater is already working... Please wait a few moments and try again");
                            }
                            return true;
                        }
                        $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "§aPlease use: §b") . "/essentialspe update <check|install>");
                        break;
                    case "version":
                    case "v":
                        $sender->sendMessage(TextFormat::YELLOW . "§aYou're using §b" . TextFormat::AQUA . "§bEssentialsPE " . TextFormat::YELLOW . "§6v" . TextFormat::GOLD . $sender->getServer()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion());
                        break;
                    default:
                        $this->sendUsage($sender, $alias);
                        return false;
                        break;
                }
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
}
