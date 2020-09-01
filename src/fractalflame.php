<?php

namespace FractalFlame;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__. "/variations.php";

$colorPalette = new ColorPalette(__DIR__ . "/colorPalettes/sky-flesh.map");

$sierpinskiGasket = [
	function ($x, $y)
	{
		$c = 0.0;
		return variations\linear($x * 0.5 + $y * 0 + $c, $x * 1 + $y * 0.5 + 0.5);
	},
	function ($x, $y)
	{
		return variations\linear($x * 0.5 + $y * 0 + 1, $x * 0 + $y * 0.5 + 0);
	},
	function ($x, $y)
	{
		return variations\linear($x * 0.5 + $y * 0 + 0, $x * 0 + $y * 0.5 + 1);
	}
];
$sierpinskiGasketWeights = [0.33, 0.66, 1];
$sierpinskiGasketColors = [0, 0.5, 1];

$barnsleyFern = [
	function ($x, $y)
	{
		return variations\linear($x * 0 + $y * 0 + 0, $x * 0 + $y * 0.16 + 0);
	},
	function ($x, $y)
	{
		return variations\linear($x * 0.85 + $y * 0.04 + 0, $x * -0.04 + $y * 0.85 + 1.6);
	},
	function ($x, $y)
	{
		return variations\linear($x * 0.2 + $y * -0.26 + 0, $x * 0.23 + $y * 0.22 + 1.6);
	},
	function ($x, $y)
	{
		return variations\linear($x * -0.15 + $y * 0.28 + 0, $x * 0.26 + $y * 0.24 + 0.44);
	}
];
$barnsleyFernWeights = [0.01, 0.86 , 0.93, 1];
$barnsleyFernColors = [0, 0.3 , 0.6, 1];

$flame1 = [
	function ($x, $y)
	{
		return variations\swirl($x * 1 + $y * 0 + 0, $x * 0 + $y * 1 + 0);
	},
	function ($x, $y)
	{
		return variations\swirl($x * .5 + $y * 0 + 1, $x * 0 + $y * .5 + 1);
	},
	function ($x, $y)
	{
		return variations\spherical($x * 2 + $y * 0 + 0, $x * 0 + $y * 2 + 0);
	},
];
$flame1Weights = [0.3, 0.6, 1];
$flame1Colors = [0.0, 0.5, 1];

$iterations = 20000000;
$imageSize = 2024 / 2;

$iterationsPerStep = $iterations / 100;
$iterationStep = 0;
$progress = 0;

$zoom = 0.3;

$currentFlame = $flame1;
$currentWeight = $flame1Weights;
$currentColorArray = $flame1Colors;

$image = imagecreatetruecolor($imageSize, $imageSize);
$imageColorIndex = [];
for ($x = 0; $x < $imageSize; $x++)
{
	for ($y = 0; $y < $imageSize; $y++)
	{
		$imageColorIndex[$x][$y] = 0;
	}
}

$x = rand(-$imageSize/2, $imageSize/2) / $imageSize;
$y = rand(-$imageSize/2, $imageSize/2) / $imageSize;
for ($i = 0; $i < $iterations; $i++)
{
	$rand = rand(0, 100) / 100;
	$currentColor = 0;

	for ($f = 0; $f < count($currentFlame); $f++)
	{
		if ($rand <= $currentWeight[$f])
		{
			$randomFunction = $currentFlame[$f];
			$currentColor = $currentColorArray[$f];
			break;
		}
	}

	list($x, $y) = $randomFunction($x, $y);

	$xMapped = $x * $imageSize/2 * $zoom + $imageSize/2;
	$yMapped = $y * $imageSize/2 * $zoom + $imageSize/2;

	if( $xMapped < 0 || $xMapped > $imageSize || $yMapped < 0 || $yMapped > $imageSize)
		continue;

	$imageColorIndex[$xMapped][$imageSize - $yMapped] = ($imageColorIndex[$xMapped][$imageSize - $yMapped] + $currentColor) / 2;

	$col = imagecolorat($image, $xMapped, $imageSize - $yMapped) + 1;
	imagesetpixel($image, $xMapped, $imageSize - $yMapped, $col);

	if( ++$iterationStep > $iterationsPerStep )
	{
		$iterationStep = 0;
		$progress += 1;
		echo "progress:$progress\n";
	}
}

// coloring
{
	echo "coloring flame\n";
	$max = 0;
	for ($i = 0; $i < $imageSize; $i++)
	{
		for ($j = 0; $j < $imageSize; $j++)
		{
			$col = imagecolorat($image, $i, $j);
			$max = max($max, $col);
		}
	}

	$max = log($max);

	if($max == 0)
		return;

	$colorInterpolate = function ($t, $weight) use ($colorPalette)
	{
		$color = $colorPalette->color[$t * 255];
		$result = [];
		$result[] = $color[0] * $weight;
		$result[] = $color[1] * $weight;
		$result[] = $color[2] * $weight;
		return $result;
	};

	for ($i = 0; $i < $imageSize; $i++)
	{
		for ($j = 0; $j < $imageSize; $j++)
		{
			$col = imagecolorat($image, $i, $j);

			$value = (log($col) / $max);

			$gamma = 4;
			$value = $value ** (1/$gamma);

			$cFinal = $colorInterpolate($imageColorIndex[$i][$j], $value);
			$newCol = (int)$cFinal[2];
			$newCol += (int)$cFinal[1] << 8;
			$newCol += (int)$cFinal[0] << 16;

			imagesetpixel($image, $i, $j, $newCol);
		}
	}
}

imagepng($image, "output.png", 6);
