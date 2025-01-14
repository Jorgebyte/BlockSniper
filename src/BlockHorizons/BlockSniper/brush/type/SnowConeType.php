<?php

declare(strict_types=1);

namespace BlockHorizons\BlockSniper\brush\type;

use BlockHorizons\BlockSniper\brush\Type;
use Generator;
use pocketmine\block\Air;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\Flowable;
use pocketmine\block\SnowLayer;
use pocketmine\block\VanillaBlocks;
use pocketmine\math\Facing;

/*
 * Lays a layer of snow on top of the terrain, and raises it if there is snow already.
 */

class SnowConeType extends Type{

	public function fill() : Generator{
		foreach($this->mustGetBlocks() as $block){
			if(!($block instanceof Flowable) && !($block instanceof Air)){
				$topBlock = $this->side($block->getPosition(), Facing::UP);
				if($topBlock instanceof Air || $topBlock instanceof SnowLayer){
					if($topBlock->getStateId() < 7 && $topBlock->getTypeId() === BlockTypeIds::SNOW_LAYER){
						yield $topBlock;
						$this->putBlock($topBlock->getPosition(), BlockTypeIds::getInstance()->get(BlockTypeIds::SNOW_LAYER, $topBlock->getStateId() + 1));
					}elseif(!($topBlock instanceof SnowLayer)){
						yield $topBlock;
						$this->putBlock($topBlock->getPosition(), VanillaBlocks::SNOW_LAYER());
					}
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Snow Cone";
	}

	/**
	 * @return bool
	 */
	public function usesBrushBlocks() : bool{
		return false;
	}
}
