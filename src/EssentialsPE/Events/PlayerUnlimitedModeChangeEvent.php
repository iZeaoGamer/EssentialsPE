<?php

declare(strict_types = 1);

namespace EssentialsPE\Events;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCustomEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerUnlimitedModeChangeEvent extends BaseCustomEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Player  */
    protected $player;
    /** @var bool  */
    protected $isEnabled;
    /** @var  bool */
    protected $mode;

    /**
     * @param BaseAPI $api
     * @param Player $player
     * @param bool $mode
     */
    public function __construct(BaseAPI $api, Player $player, bool $mode){
        parent::__construct($api);
        $this->player = $player;
        $this->isEnabled = $api->isUnlimitedEnabled($player);
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
     * Tell is the player already have the Unlimited Placing of items enabled
     *
     * @return bool
     */
    public function isUnlimitedEnabled(): bool{
        return $this->isEnabled;
    }

    /**
     * Tell the mode to be set
     *
     * @return bool
     */
    public function getUnlimitedMode(): bool{
        return $this->mode;
    }

    /**
     * Change the mode to be set
     * false = Unlimited will be disabled
     * true = Unlimited will be enabled
     *
     * @param bool $mode
     */
    public function setUnlimitedMode(bool $mode): void{
        $this->mode = $mode;
    }
} 