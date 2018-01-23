<?php

declare(strict_types = 1);

namespace EssentialsPE\Events;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCustomEvent;
use pocketmine\Player;

class SessionCreateEvent extends BaseCustomEvent{
    public static $handlerList = null;

    /** @var Player  */
    public $player;
    /** @var array  */
    public $values;

    /**
     * @param BaseAPI $api
     * @param Player $player
     * @param array $values
     */
    public function __construct(BaseAPI $api, Player $player, array $values){
        parent::__construct($api);
        $this->player = $player;
        $this->values = $values;
    }

    /**
     * return the Player to work on
     *
     * @return Player
     */
    public function getPlayer(): Player{
        return $this->player;
    }

    /**
     * Return all the Session Values
     *
     * @return array
     */
    public function getValues(): array{
        return $this->values;
    }

    /**
     * Replace a specific Session Value
     *
     * @param string $key
     * @param mixed $value
     */
    public function setValue(string $key, $value): void{
        if(!isset($this->values[$key])){
            return;
        }
        $this->values[$key] = $value;
    }

    /**
     * Set the Session Values
     *
     * @param array $values
     */
    public function setValues(array $values): void{
        $this->values = $values;
    }
} 