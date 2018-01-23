<?php

declare(strict_types = 1);

namespace EssentialsPE\Events;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCustomEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerAFKModeChangeEvent extends BaseCustomEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Player */
    protected $player;
    /** @var bool */
    protected $isAFK;
    /** @var bool */
    protected $mode;
    /** @var bool */
    protected $broadcast;

    /**
     * @param BaseAPI $api
     * @param Player $player
     * @param bool $mode
     * @param bool $broadcast
     */
    public function __construct(BaseAPI $api, Player $player, bool $mode, bool $broadcast){
        parent::__construct($api);
        $this->player = $player;
        $this->isAFK = $api->isAFK($player);
        $this->mode = $mode;
        $this->broadcast = $broadcast;
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
     * Tell if the player is already AFK or not
     *
     * @return bool
     */
    public function isAFK(): bool{
        return $this->isAFK;
    }

    /**
     * Tell the mode will to be set
     *
     * @return bool
     */
    public function getAFKMode(): bool{
        return $this->mode;
    }

    /**
     * Change the mode to be set
     * false = Player will not be AFK
     * true = Player will be AFK
     *
     * @param bool $mode
     */
    public function setAFKMode(bool $mode): void{
        $this->mode = $mode;
    }

    /**
     * Tell if the AFK status will be broadcast
     *
     * @return bool
     */
    public function getBroadcast(): bool{
        return $this->broadcast;
    }

    /**
     * Specify if the AFK status will be broadcast
     *
     * @param bool $mode
     */
    public function setBroadcast(bool $mode): void{
        $this->broadcast = $mode;
    }
} 