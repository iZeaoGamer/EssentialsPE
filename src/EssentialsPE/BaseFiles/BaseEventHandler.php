<?php

declare(strict_types = 1);

namespace EssentialsPE\BaseFiles;

use EssentialsPE\Loader;
use pocketmine\event\Listener;

abstract class BaseEventHandler implements Listener{
    /** @var BaseAPI */
    private $api;

    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        $this->api = $api;
    }

    /**
     * @return Loader
     */
    public final function getPlugin(): Loader{
        return $this->getAPI()->getEssentialsPEPlugin();
    }

    /**
     * @return BaseAPI
     */
    public final function getAPI(): BaseAPI{
        return $this->api;
    }
}