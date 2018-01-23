<?php

declare(strict_types = 1);

namespace EssentialsPE\Events;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCustomEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerVanishEvent extends BaseCustomEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Player  */
    protected $player;
    /** @var bool  */
    protected $isVanished;
    /** @var bool */
    protected $willVanish;
    /** @var bool */
    protected $noPacket;
    /** @var array */
    protected $keepHiddenFor = [];

    /**
     * @param BaseAPI $api
     * @param Player $player
     * @param bool $willVanish
     * @param bool $noPacket
     */
    public function __construct(BaseAPI $api, Player $player, bool $willVanish, bool $noPacket){
        parent::__construct($api);
        $this->player = $player;
        $this->isVanished = $api->isVanished($player);
        $this->willVanish = $willVanish;
        $this->noPacket = $noPacket;
    }

    /**
     * Return the player that will be vanished/shown
     *
     * @return Player
     */
    public function getPlayer(): Player{
        return $this->player;
    }

    /**
     * Tell if the player is already vanished or not
     *
     * @return bool
     */
    public function isVanished(): bool{
        return $this->isVanished;
    }

    /**
     * Tell if the player will be vanished or showed
     * false = Player will be showed
     * true = Player will be vanished
     *
     * @return bool
     */
    public function willVanish(): bool{
        return $this->willVanish;
    }

    /**
     * Change the vanish mode that will be set
     * false = Player will be shown
     * true = Player will be vanished
     *
     * @param bool $value
     */
    public function setVanished(bool $value): void{
        $this->willVanish = $value;
    }

    /**
     * Tell if you prefer to use (or not) Player Packets instead of Effect ones.
     * false = Use Effect Packets
     * true = Use Player Packets
     *
     * @return bool
     */
    public function noPacket(): bool{
        return $this->noPacket;
    }

    /**
     * Change the Packets to be used in the event.
     * false = Use Effect Packets
     * true = Use Player Packets
     *
     * @param bool $state
     */
    public function setNoPacket(bool $state): void{
        $this->noPacket = $state;
    }

    /**
     * This method will allow you to keep a player
     * hidden to other players, but EssentialsPE
     * will no longer consider it has "Vanished"
     *
     * @param Player $player
     */
    public function keepHiddenFor(Player $player): void{
        $this->keepHiddenFor[] = $player->getName();
    }

    /**
     * Return a list with all the players that
     * will not see the "unVanished" player
     *
     * @return array
     */
    public function getHiddenFor(): array{
        return $this->keepHiddenFor;
    }
} 