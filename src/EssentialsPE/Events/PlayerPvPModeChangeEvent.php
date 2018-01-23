<?php

declare(strict_types = 1);

namespace EssentialsPE\Events;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCustomEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerPvPModeChangeEvent extends BaseCustomEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Player  */
    protected $player;
    /** @var bool  */
    protected $isEnabled;
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
        $this->isEnabled = $api->isPvPEnabled($player);
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
     * Tell if the player already have PvP enabled
     *
     * @return bool
     */
    public function isPvPEnabled(): bool{
        return $this->isEnabled;
    }

    /**
     * Tell the mode to be set
     *
     * @return bool
     */
    public function getPvPMode(): bool{
        return $this->mode;
    }

    /**
     * Change the PVP mode
     * false = PvP mode will be disabled for the player
     * true = PvP mode will be enabled for the player
     *
     * @param bool $mode
     */
    public function setPvPMode(bool $mode): void{
        $this->mode = $mode;
    }
} 