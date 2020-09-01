<?php


namespace FractalFlame\flames;


use FractalFlame\Flame;
use function FractalFlame\variations\spherical;
use function FractalFlame\variations\swirl;

class CustomFlame1
{
	public static function Create(): Flame
	{
		return new Flame(
			[
				function ($x, $y)
				{
					return swirl($x * 1 + $y * 0 + 0, $x * 0 + $y * 1 + 0);
				},
				function ($x, $y)
				{
					return swirl($x * .5 + $y * 0 + 1, $x * 0 + $y * .5 + 1);
				},
				function ($x, $y)
				{
					return spherical($x * 2 + $y * 0 + 0, $x * 0 + $y * 2 + 0);
				},
			],
			[0.3, 0.6, 1],
			[0.0, 0.5, 1]);
	}
}