<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Override;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;

abstract class BaseOverrideCommand extends BaseCommand{
    /**
     * @param BaseAPI $api
     * @param string $name
     * @param string $description
     * @param string $usageMessage
     * @param bool|null|string $consoleUsageMessage
     * @param array $aliases
     */
    public function __construct(BaseAPI $api, string $name, string $description = "", string $usageMessage = "", $consoleUsageMessage = true, array $aliases = []){
        parent::__construct($api, $name, $description, $usageMessage, $consoleUsageMessage, $aliases);
        // Special part :D
        $commandMap = $api->getServer()->getCommandMap();
        $command = $commandMap->getCommand($name);
        $command->setLabel($name . "_disabled");
        $command->unregister($commandMap);
    }
}