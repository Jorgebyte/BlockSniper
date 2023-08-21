<?php

declare(strict_types=1);

namespace BlockHorizons\BlockSniper\brush\type;

use BlockHorizons\BlockSniper\brush\Type;
use Generator;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\VanillaBlocks;

/*
 * Freezes the terrain, causing water to become ice, lava to become obsidian and extinguishes fire.
 */

class FreezeType extends Type{

	public function fill() : Generator{
		foreach($this->mustGetBlocks() as $block){
			switch($block->getTypeId()){
				case BlockTypeIds::WATER:
					yield $block;
					$this->putBlock($block->getPosition(), VanillaBlocks::ICE());
					break;
				case BlockTypeIds::LAVA:
					yield $block;
					$this->putBlock($block->getPosition(), VanillaBlocks::OBSIDIAN());
					break;
				case BlockTypeIds::FIRE:
					yield $block;
					$this->delete($block->getPosition());
					break;
				case BlockTypeIds::ICE:
					yield $block;
					$this->putBlock($block->getPosition(), VanillaBlocks::PACKED_ICE());
			}
		}
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Freeze";
	}

	/**
	 * @return bool
	 */
	public function usesBrushBlocks() : bool{
		return false;
	}
}