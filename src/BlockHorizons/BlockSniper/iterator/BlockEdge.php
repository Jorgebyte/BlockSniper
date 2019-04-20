<?php

declare(strict_types=1);

namespace BlockHorizons\BlockSniper\iterator;

use pocketmine\math\Vector3;

class BlockEdge{

	/** @var Vector3 */
	private $start, $end;

	public function __construct(Vector3 $start, Vector3 $end){
		$this->start = $start;
		$this->end = $end;
	}

	/**
	 * walk walks from the start of the edge to the end, taking steps of $interval size. The generator yields Vector3s.
	 *
	 * @param float $interval
	 *
	 * @return \Generator
	 */
	public function walk(float $interval = 0.1) : \Generator{
		$sub = $this->end->subtract($this->start)->multiply($interval);
		$iterCount = 1 / $interval;
		for($i = 0; $i < $iterCount; $i++) {
			yield $this->start->add($sub->multiply($i));
		}
	}
}