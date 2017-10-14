<?php

declare(strict_types = 1);

namespace BlockHorizons\BlockSniper\brush;

use BlockHorizons\BlockSniper\brush\registration\ShapeRegistration;
use BlockHorizons\BlockSniper\brush\registration\TypeRegistration;
use BlockHorizons\BlockSniper\events\ChangeBrushPropertiesEvent as Change;
use BlockHorizons\BlockSniper\InvalidShapeException;
use BlockHorizons\BlockSniper\InvalidTypeException;
use BlockHorizons\BlockSniper\Loader;
use BlockHorizons\BlockSniper\sessions\PlayerSession;
use BlockHorizons\BlockSniper\sessions\Session;

class PropertyProcessor {

	const VALUE_SIZE = 0;
	const VALUE_SHAPE = 1;
	const VALUE_TYPE = 2;
	const VALUE_HOLLOW = 3;
	const VALUE_DECREMENT = 4;
	const VALUE_HEIGHT = 5;
	const VALUE_PERFECT = 6;
	const VALUE_BLOCKS = 7;
	const VALUE_OBSOLETE = 8;
	const VALUE_BIOME = 9;
	const VALUE_TREE = 10;
	
	/** @var Session */
	private $session = null;
	/** @var Loader */
	private $loader = null;

	public function __construct(Session $session, Loader $loader) {
		$this->session = $session;
		$this->loader = $loader;
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @param int|string $valueType
	 * @param            $value
	 */
	public function process($valueType, $value): void {
		$brush = $this->session->getBrush();
		switch($valueType) {
			case "size":
			case 0:
				$brush->setSize((int) $value);
				$action = Change::ACTION_CHANGE_SIZE;
				break;

			case "shape":
			case 1:
				$name = ShapeRegistration::getShapeById($value, true);
				if(!ShapeRegistration::shapeExists($value)) {
					throw new InvalidShapeException("Invalid shape passed to property processor.");
				}
				$brush->setShape($name);
				$action = Change::ACTION_CHANGE_SHAPE;
				break;

			case "type":
			case 2:
				$name = TypeRegistration::getTypeById($value, true);
				if(!TypeRegistration::typeExists($value)) {
					throw new InvalidTypeException("Invalid type passed to property processor.");
				}
				$brush->setType($name);
				$action = Change::ACTION_CHANGE_TYPE;
				break;

			case "hollow":
			case 3:
				$brush->setHollow((bool) $value);
				$action = Change::ACTION_CHANGE_HOLLOW;
				break;

			case "decrement":
			case 4:
				$brush->setDecrementing((bool) $value);
				$brush->resetSize = $brush->getSize();
				$action = Change::ACTION_CHANGE_DECREMENT;
				break;

			case "height":
			case 5:
				$brush->setHeight((int) $value);
				$action = Change::ACTION_CHANGE_HEIGHT;
				break;

			case "perfect":
			case 6:
				$brush->setPerfect((bool) $value);
				$action = Change::ACTION_CHANGE_PERFECT;
				break;

			case "blocks":
			case 7:
				$blocks = explode(",", $value);
				$brush->setBlocks($blocks);
				$action = Change::ACTION_CHANGE_BLOCKS;
				break;

			case "obsolete":
			case 8:
				$blocks = explode(",", $value);
				$brush->setObsolete($blocks);
				$action = Change::ACTION_CHANGE_OBSOLETE;
				break;

			case "biome":
			case 9:
				$brush->setBiome($value);
				$action = Change::ACTION_CHANGE_BIOME;
				break;

			case "tree":
			case 10:
				$brush->setTree($value);
				$action = Change::ACTION_CHANGE_TREE;
				break;

			default:
				return;
		}
		if($this->session instanceof PlayerSession) {
			$this->getLoader()->getServer()->getPluginManager()->callEvent(new Change($this->session->getSessionOwner()->getPlayer(), $action, $value));
		}
	}
}