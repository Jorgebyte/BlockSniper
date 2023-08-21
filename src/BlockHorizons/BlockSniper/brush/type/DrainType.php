<?php

declare(strict_types=1);

namespace BlockHorizons\BlockSniper\brush\type;

use BlockHorizons\BlockSniper\brush\Type;
use Generator;
use pocketmine\block\BlockTypeIds;

/*
 * Removes all liquid blocks within the brush radius.
 */

class DrainType extends Type{

	private const LIQUID_BLOCKS = [
        BlockTypeIds::WATER => 0,
        BlockTypeIds::LAVA => 0
	];

	public function fill() : Generator{
		foreach($this->mustGetBlocks() as $block){
			if(isset(self::LIQUID_BLOCKS[$block->getTypeId()])){
				yield $block;
				$this->delete($block->getPosition());
			}
		}
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Drain";
	}

	/**
	 * @return bool
	 */
	public function usesBrushBlocks() : bool{
		return false;
	}
}