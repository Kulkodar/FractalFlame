<?php


namespace FractalFlame;

/**
 * Structure to store all needed information to generate a flame.
 */
class Flame
{
	/**
	 * @param array $functions the set of functions with the signature f(x,y) = V(ax+by+c, dx+ey+f) where v is a variation @see variations.php.
	 * @param array $weights the probability of the function @note:(probability of the function + the sum of the previous functions probabilities).
	 * @param array $colors the color index of the function.
	 *
	 * Example: Sierpinski’s Gasket
	 * new Flame(
	 * [
	 * 	function($x,$y){ return[ $x/2, $y/2 ]; }, //probability 1/3
	 * 	function($x,$y){ return[ ($x+1)/2, $y/2 ]; }, //probability 1/3
	 * 	function($x,$y){ return[ $x/2, ($y+1)/2 ]; } //probability 1/3
	 * ],
	 * [1/3, 2/3, 1],
	 * [0, 0.5, 1] );
	 */
	public function __construct(array $functions, array $weights, array $colors)
	{
		$this->colors = $colors;
		$this->functions = $functions;
		$this->weights = $weights;
	}

	public $functions;
	public $weights;
	public $colors;
}