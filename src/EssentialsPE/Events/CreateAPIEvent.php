<?php

declare(strict_types = 1);

namespace EssentialsPE\Events;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\Loader;
use pocketmine\event\plugin\PluginEvent;

class CreateAPIEvent extends PluginEvent{
    public static $handlerList = null;

    /** @var string */
    private $class;

    /**
     * @param Loader $plugin
     * @param BaseAPI::class $api
     */
    public function __construct(Loader $plugin, string $api){
        parent::__construct($plugin);
        if(!is_a($api, BaseAPI::class, true)){
            throw new \RuntimeException("Class $api must extend " . BaseAPI::class);
        }
        $this->class = BaseAPI::class;
    }

    /**
     * @return string
     */
    public function getClass(): string{
        return $this->class;
    }

    /**
     * @param BaseAPI::class $api
     */
    public function setClass(string $class): void{
        if(!is_a($class, BaseAPI::class, true)){
            throw new \RuntimeException("Class $class must extend " . BaseAPI::class);
        }
        $this->class = $class;
    }
}