<?php

declare(strict_types = 1);

namespace EssentialsPE\Tasks\Updater;

use EssentialsPE\BaseFiles\BaseTask;
use EssentialsPE\BaseFiles\BaseAPI;
use pocketmine\utils\TextFormat;

class AutoFetchCallerTask extends BaseTask{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api);
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick): void{
        $this->getAPI()->getServer()->getLogger()->debug(TextFormat::YELLOW . "Running EssentialsPE's AutoFetchCallerTask");
        $this->getAPI()->fetchEssentialsPEUpdate(false);
    }
}