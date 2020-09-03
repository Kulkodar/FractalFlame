<?php


namespace FractalFlame\flames;


use FractalFlame\Flame;
use function FractalFlame\variations\linear;


/**
 * Factory class to create a flame that generates Sierpinski's Gasket.
 */
class SierpinskiGasket
{
	public static function Create(): Flame
	{
		return new Flame(
			[
				function ($x, $y)
				{
					$c = 0.0;
					return linear($x * 0.5 + $y * 0 + $c, $x * 1 + $y * 0.5 + 0.5);
				},
				function ($x, $y)
				{
					return linear($x * 0.5 + $y * 0 + 1, $x * 0 + $y * 0.5 + 0);
				},
				function ($x, $y)
				{
					return linear($x * 0.5 + $y * 0 + 0, $x * 0 + $y * 0.5 + 1);
				}
			],
			[0.33, 0.66, 1],
			[0, 0.5, 1]);
	}
}