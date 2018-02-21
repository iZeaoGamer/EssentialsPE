<?php

declare(strict_types = 1);

namespace EssentialsPE\BaseFiles;

use pocketmine\level\Level;
use pocketmine\level\Location;
class BaseLocation extends Location{
    /** @var string */
    protected $name;
    /** @var float */
    protected $yaw;
    /** @var float */
    protected $pitch;
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
    public function __construct(string $name, int $x = 0, int $y = 0, int $z = 0, float $yaw = 0.0, float $pitch = 0.0, Level $level = null){
        parent::__construct($x, $y, $z, $level);
        $this->name = $name;
	$this->yaw = $yaw;
	$this->pitch = $pitch;
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
