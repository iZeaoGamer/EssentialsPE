<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class BreakCommand extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "break", "Breaks the block you're looking at", "", false);
        $this->setPermission("essentials.break.use");
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
        if(!$sender instanceof Player || count($args) !== 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(($block = $sender->getTargetBlock(100, [Block::AIR])) === null){
            $sender->sendMessage(TextFormat::RED . "There isn't a reachable block");
            return false;
        }elseif($block->getId() === Block::BEDROCK && !$sender->hasPermission("essentials.break.bedrock")){
            $sender->sendMessage(TextFormat::RED . "You can't break bedrock");
            return false;
        }
        $sender->getLevel()->setBlock($block, new Air(), true, true);
        return true;
    }
} 