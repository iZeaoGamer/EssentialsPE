<?php

declare(strict_types = 1);

namespace EssentialsPE\Events;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCustomEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerMuteEvent extends BaseCustomEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Player  */
    protected $player;
    /** @var  bool */
    protected $isMuted;
    /** @var  bool */
    protected $mode;
    /** @var \DateTime|null */
    protected $expires;

    /**
     * @param BaseAPI $api
     * @param Player $player
     * @param bool $mode
     * @param \DateTime $expires
     */
    public function __construct(BaseAPI $api, Player $player, bool $mode, \DateTime $expires = null){
        parent::__construct($api);
        $this->player = $player;
        $this->isMuted = $api->isMuted($player);
        $this->mode = $mode;
        $this->expires = $expires;
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
     * Tell is the player is already muted
     *
     * @return bool
     */
    public function isMuted(): bool{
        return $this->isMuted;
    }

    /**
     * Tell if the player will be muted or not
     *
     * @return bool
     */
    public function willMute(): bool{
        return $this->mode;
    }

    /**
     * Change the Mute mode to be set
     * false = Player will not be muted
     * true = Player will be muted
     *
     * @param bool $mode
     */
    public function setMuted(bool $mode): void{
        $this->mode = $mode;
    }

    /**
     * Tells the time the mute state will stay
     * int = "Date Time format" of expiration
     * null = Will keep forever
     *
     * @return \DateTime|null
     */
    public function getMutedUntil(): ?\DateTime{
        return $this->expires;
    }

    /**
     * Set how long the mute will be applied
     * int = "Date Time format" of expiration
     * null = Will keep forever
     *
     * @param \DateTime|null $expires
     */
    public function setMutedUntil(\DateTime $expires = null): void{
        $this->expires = $expires;
    }
} 