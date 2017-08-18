<?php

declare(strict_types = 1);

namespace BlockHorizons\BlockSniper\brush;

use BlockHorizons\BlockSniper\data\Translation;
use BlockHorizons\BlockSniper\events\BrushRecoverEvent;
use BlockHorizons\BlockSniper\Loader;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class BrushManager {

	/** @var Brush[] */
	private static $brush = [];
	/** @var Loader */
	private $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
		if($loader->getSettings()->saveBrushProperties()) {
			$brushes = [];
			if(is_file($loader->getDataFolder() . "brushes.yml")) {
				$brushes = yaml_parse_file($loader->getDataFolder() . "brushes.yml");
				unlink($loader->getDataFolder() . "brushes.yml");
			}
			if(!empty($brushes)) {
				foreach($brushes as $playerName => $brush) {
					$this->getLoader()->getServer()->getPluginManager()->callEvent($event = new BrushRecoverEvent($this->getLoader(), $playerName, unserialize($brush)));
					if($event->isCancelled()) {
						continue;
					}
					self::$brush[$playerName] = unserialize($brush);
					$loader->getLogger()->debug(TF::GREEN . (new Translation(Translation::LOG_BRUSH_RESTORED, [$playerName]))->getMessage());
				}
			}
			$loader->getLogger()->info(TF::GREEN . (new Translation(Translation::LOG_BRUSH_ALL_RESTORED))->getMessage());
		}
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @param Player $player
	 *
	 * @return Brush|null
	 */
	public static function get(Player $player) {
		if(isset(self::$brush[$player->getName()])) {
			return self::$brush[$player->getName()];
		}
		return null;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function createBrush(Player $player): bool {
		if(isset(self::$brush[$player->getName()])) {
			return false;
		}
		self::$brush[$player->getName()] = new Brush($player->getName());
		return true;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function resetBrush(Player $player): bool {
		if(isset(self::$brush[$player->getName()])) {
			unset(self::$brush[$player->getName()]);
			return true;
		}
		return false;
	}

	public function storeBrushesToFile() {
		$data = [];
		foreach(self::$brush as $playerName => $brush) {
			$data[$playerName] = serialize($brush);
		}
		yaml_emit_file($this->getLoader()->getDataFolder() . "brushes.yml", $data);
	}
}
