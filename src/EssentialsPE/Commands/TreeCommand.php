<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\block\Sapling;
use pocketmine\command\CommandSender;
use pocketmine\level\generator\object\Tree;
use pocketmine\Player;
use pocketmine\utils\Random;
use pocketmine\utils\TextFormat;

class TreeCommand extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tree", "Spawns a tree", "<tree|birch|redwood|jungle>", false);
        $this->setPermission("essentials.tree");
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
        $block = $sender->getTargetBlock(100, BaseAPI::NON_SOLID_BLOCKS);
        if($block === null){
            $sender->sendMessage(TextFormat::RED . "There isn't a reachable block");
            return false;
        }
        switch(strtolower($args[0])){
            case "oak":
            default:
                $type = Sapling::OAK;
                break;
            case "birch":
                $type = Sapling::BIRCH;
                break;
            case "redwood":
                $type = Sapling::SPRUCE;
                break;
            case "jungle":
                $type = Sapling::JUNGLE;
                break;
            /*case "redmushroom":
                $type = Sapling::RED_MUSHROOM;
                break;
            case "brownmushroom":
                $type = Sapling::BROWN_MUSHROOM;
                break;
            case "swamp":
                $type = Sapling::SWAMP;
                break;*/
        }
        Tree::growTree($sender->getLevel(), $block->x, $block->y+1, $block->z, new Random(mt_rand()), $type & 0x07);
        return true;
    }
} 