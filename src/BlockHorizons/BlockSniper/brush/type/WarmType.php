<?php

declare(strict_types=1);

namespace BlockHorizons\BlockSniper\brush\type;

use BlockHorizons\BlockSniper\brush\Type;
use Generator;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\VanillaBlocks;

class WarmType extends Type{

	public function fill() : Generator{
		foreach($this->mustGetBlocks() as $block){
			switch($block->getTypeId()){
				case BlockTypeIds::ICE:
					yield $block;
					$this->putBlock($block->getPosition(), VanillaBlocks::WATER());
					break;
				case BlockTypeIds::SNOW_LAYER:
					yield $block;
					$this->delete($block->getPosition());
					break;
				case BlockTypeIds::PACKED_ICE:
					yield $block;
					$this->putBlock($block->getPosition(), VanillaBlocks::ICE());
			}
		}
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Warm";
	}

	/**
	 * @return bool
	 */
	public function usesBrushBlocks() : bool{
		return false;
	}
}