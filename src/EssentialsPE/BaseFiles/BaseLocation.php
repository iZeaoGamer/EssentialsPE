<?php

declare(strict_types = 1);

namespace EssentialsPE\BaseFiles;

use pocketmine\level\Level;
use pocketmine\level\Location;

class BaseLocation extends Location{

    /** @var string */
    protected $name;
    /** @var string */
    protected $levelName;

    /**
     * @param string $name
     * @param int $x
     * @param int $y
     * @param int $z
     * @param Level $level
     * @param float $yaw
     * @param float $pitch
     */
    public function __construct(string $name, int $x, int $y, int $z, Level $level, float $yaw, float $pitch){
        parent::__construct($x, $y, $z, $yaw, $pitch, $level);
        $this->name = $name;
        $this->levelName = $level->getName();
    }

    /**
     * @return string
     */
    public function getName(): string{
        return $this->name;
    }

	/**
	 * @return string
	 */
    public function getLevelName(): string{
    	return $this->levelName;
    }

    /**
     * @param string $name
     * @param Location $pos
     * @return BaseLocation
     */
    public static function fromPosition(string $name, Location $pos): BaseLocation{
        return new BaseLocation($name, (int) $pos->getX(), (int) $pos->getY(), (int) $pos->getZ(), $pos->getLevel(), $pos->getYaw(), $pos->getPitch());
    }
}