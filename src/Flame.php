<?php


namespace FractalFlame;


class Flame
{
	public function __construct( $functions, $weights, $colors)
	{
		$this->colors = $colors;
		$this->functions = $functions;
		$this->weights = $weights;
	}

	public $functions;
	public $weights;
	public $colors;
}