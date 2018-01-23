<?php

declare(strict_types = 1);

namespace EssentialsPE\Events;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCustomEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerGodModeChangeEvent extends BaseCustomEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Player */
    protected $player;
    /** @var bool  */
    protected $isGod;
    /** @var bool  */
    protected $mode;

    /**
     * @param BaseAPI $api
     * @param Player $player
     * @param bool $mode
     */
    public function __construct(BaseAPI $api, Player $player, bool $mode){
        parent::__construct($api);
        $this->player = $player;
        $this->isGod = $api->isGod($player);
        $this->mode = $mode;
    }

    /**
     * Return the player to be used
     *
     * @return Player
     */
    public function getPlayer(): Player{
        return $this->player;
    }

    /**
     * Tell if the player is already in God mode
     *
     * @return bool
     */
    public function isGod(): bool{
        return $this->isGod;
    }

    /**
     * Tell if the player will get the God mode or not
     *
     * @return bool
     */
    public function getGodMode(): bool{
        return $this->mode;
    }

    /**
     * Change the mode to be set
     * false = Player will not become God
     * true = Player will get the God mode
     *
     * @param bool $mode
     */
    public function setGodMode(bool $mode): void{
        $this->mode = $mode;
    }
} 