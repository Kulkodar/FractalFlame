<?php


namespace FractalFlame\flames;


use FractalFlame\Flame;
use function FractalFlame\variations\linear;

class BarnsleyFern
{
	public static function Create(): Flame
	{
		return new Flame(
			[
				function ($x, $y)
				{
					return linear($x * 0 + $y * 0 + 0, $x * 0 + $y * 0.16 + 0);
				},
				function ($x, $y)
				{
					return linear($x * 0.85 + $y * 0.04 + 0, $x * -0.04 + $y * 0.85 + 1.6);
				},
				function ($x, $y)
				{
					return linear($x * 0.2 + $y * -0.26 + 0, $x * 0.23 + $y * 0.22 + 1.6);
				},
				function ($x, $y)
				{
					return linear($x * -0.15 + $y * 0.28 + 0, $x * 0.26 + $y * 0.24 + 0.44);
				}
			],
			[0.01, 0.86, 0.93, 1],
			[0, 0.3, 0.6, 1]);
	}
}