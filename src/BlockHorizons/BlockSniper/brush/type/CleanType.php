<?php

declare(strict_types=1);

namespace BlockHorizons\BlockSniper\brush\type;

use BlockHorizons\BlockSniper\brush\Type;
use Generator;
use pocketmine\block\BlockTypeIds;

/*
 * Removes all non-natural blocks within the brush radius.
 */

class CleanType extends Type{

	private const NATURAL_BLOCKS = [
        BlockTypeIds::STONE => 0,
        BlockTypeIds::GRASS => 0,
        BlockTypeIds::DIRT => 0,
        BlockTypeIds::GRAVEL => 0,
        BlockTypeIds::SAND => 0,
        BlockTypeIds::SANDSTONE => 0
	];

	protected function fill() : Generator{
		foreach($this->mustGetBlocks() as $block){
			if(!isset(self::NATURAL_BLOCKS[$block->getTypeId()])){
				yield $block;
				$this->delete($block->getPosition());
			}
		}
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Clean";
	}

	/**
	 * @return bool
	 */
	public function usesBrushBlocks() : bool{
		return false;
	}
}
