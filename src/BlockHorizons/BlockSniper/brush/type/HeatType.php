<?php

declare(strict_types=1);

namespace BlockHorizons\BlockSniper\brush\type;

use BlockHorizons\BlockSniper\brush\Type;
use Exception;
use Generator;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\DoublePlant;
use pocketmine\block\Flower;
use pocketmine\block\Leaves;
use pocketmine\block\TallGrass;
use pocketmine\block\VanillaBlocks;

class HeatType extends Type{

	public function fill() : Generator{
		foreach($this->mustGetBlocks() as $block){
			switch($block->getTypeId()){
				case BlockTypeIds::PACKED_ICE:
				case BlockTypeIds::ICE:
					yield $block;
					$this->putBlock($block->getPosition(), VanillaBlocks::WATER());
					break;
				case BlockTypeIds::SNOW_LAYER:
				case BlockTypeIds::SNOW:
					yield $block;
					$this->delete($block->getPosition());
					break;
				case BlockTypeIds::WATER:
				case $block instanceof Leaves:
					if(random_int(0, 4) === 0){
						yield $block;
						$this->delete($block->getPosition());
					}
					break;
				case BlockTypeIds::GRASS:
					$random = random_int(0, 8);
					if($random === 0){
						yield $block;
						$this->putBlock($block->getPosition(), VanillaBlocks::DIRT());
					}elseif($random === 1){
						yield $block;
						$this->putBlock($block->getPosition(), VanillaBlocks::DIRT()->setCoarse(true));
					}
					break;
				case $block instanceof Flower || $block instanceof DoublePlant || $block instanceof TallGrass:
					yield $block;
					$this->putBlock($block->getPosition(), VanillaBlocks::DEAD_BUSH());
			}
		}
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Heat";
	}

	/**
	 * @return bool
	 */
	public function usesBrushBlocks() : bool{
		return false;
	}
}