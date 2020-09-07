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
$gamma = $arguments["gamma"] ?? 4;
$outputPath = ($arguments["o"] ?? "./output").".png";

$iterationsPerStep = $iterations / 100;
$iterationStep = 0;
$progress = 0;

$image = imagecreatetruecolor($imageSize, $imageSize);
$pixelColorIndex = [];
for ($x = 0; $x < $imageSize; $x++)
{
	for ($y = 0; $y < $imageSize; $y++)
	{
		$pixelColorIndex[$x][$y] = 0;
	}
}

$x = rand(-500000, 500000) / 1000000;
$y = rand(-500000, 500000) / 1000000;

$c = rand(-500000, 500000) / 1000000;

for ($i = 0; $i < $iterations; $i++)
{
	$rand = rand(0, 100) / 100;
	$currentColor = 0;

	for ($f = 0; $f < count($currentFlame->functions); $f++) // choose function
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
	$yMapped = $imageSize - ($y * $imageSize / 2 * $zoom + $imageSize / 2);

	if ($xMapped < 0 || $xMapped > $imageSize || $yMapped < 0 || $yMapped > $imageSize)
		continue;

	$c = ($c + $currentColor) / 2;
	$pixelColorIndex[$xMapped][$yMapped] = $c;

	$frequency = imagecolorat($image, $xMapped, $yMapped) + 1;
	imagesetpixel($image, $xMapped, $yMapped, $frequency);

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
			$frequency = imagecolorat($image, $i, $j);
			$max = max($max, $frequency);
		}
	}

	$max = log($max);

	if ($max == 0)
		return;

	$calculateFinalColor = function ($colorIndex, $weight) use ($colorPalette)
	{
		$color = $colorPalette->color[$colorIndex * 255];
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
			$frequency = imagecolorat($image, $i, $j);

			$value = (log($frequency) / $max);

			$value = $value ** (1 / $gamma);

			$cFinal = $calculateFinalColor($pixelColorIndex[$i][$j], $value);
			$newCol = (int)$cFinal[2];
			$newCol += (int)$cFinal[1] << 8;
			$newCol += (int)$cFinal[0] << 16;

			imagesetpixel($image, $i, $j, $newCol);
		}
	}
}

imagepng($image, $outputPath, 6);
