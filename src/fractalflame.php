<?php

namespace FractalFlame;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/variations.php";
require_once __DIR__ . "/checkarguments.php";

$arguments = checkArguments();

$flameName = $arguments["flame"] ?? "CustomFlame1";
$currentFlame = call_user_func(["FractalFlame\\flames\\$flameName", "Create"]);
$colorPalette = new ColorPalette(__DIR__ . "/colorPalettes/" . $arguments["colorPalette"] ?? "sky-flesh.map");
$iterations = $arguments["iterations"] ?? 1000000;
$imageSize = $arguments["imageSize"] ?? 2024;
$zoom = $arguments["zoom"] ?? 1;

$iterationsPerStep = $iterations / 100;
$iterationStep = 0;
$progress = 0;

$image = imagecreatetruecolor($imageSize, $imageSize);
$imageColorIndex = [];
for ($x = 0; $x < $imageSize; $x++)
{
	for ($y = 0; $y < $imageSize; $y++)
	{
		$imageColorIndex[$x][$y] = 0;
	}
}

$x = rand(-$imageSize / 2, $imageSize / 2) / $imageSize;
$y = rand(-$imageSize / 2, $imageSize / 2) / $imageSize;
for ($i = 0; $i < $iterations; $i++)
{
	$rand = rand(0, 100) / 100;
	$currentColor = 0;

	for ($f = 0; $f < count($currentFlame->functions); $f++)
	{
		if ($rand <= $currentFlame->weights[$f])
		{
			$randomFunction = $currentFlame->functions[$f];
			$currentColor = $currentFlame->colors[$f];
			break;
		}
	}

	list($x, $y) = $randomFunction($x, $y);

	$xMapped = $x * $imageSize / 2 * $zoom + $imageSize / 2;
	$yMapped = $y * $imageSize / 2 * $zoom + $imageSize / 2;

	if ($xMapped < 0 || $xMapped > $imageSize || $yMapped < 0 || $yMapped > $imageSize)
		continue;

	$imageColorIndex[$xMapped][$imageSize - $yMapped] = ($imageColorIndex[$xMapped][$imageSize - $yMapped] + $currentColor) / 2;

	$col = imagecolorat($image, $xMapped, $imageSize - $yMapped) + 1;
	imagesetpixel($image, $xMapped, $imageSize - $yMapped, $col);

	if (++$iterationStep > $iterationsPerStep)
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

	if ($max == 0)
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
			$value = $value ** (1 / $gamma);

			$cFinal = $colorInterpolate($imageColorIndex[$i][$j], $value);
			$newCol = (int)$cFinal[2];
			$newCol += (int)$cFinal[1] << 8;
			$newCol += (int)$cFinal[0] << 16;

			imagesetpixel($image, $i, $j, $newCol);
		}
	}
}

imagepng($image, "output.png", 6);
