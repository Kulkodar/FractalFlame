<?php

require_once "variations.php";

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

$flame1 = [
	function ($x, $y)
	{
		return variations\swirl($x * 1 + $y * 0 + 0, $x * 0 + $y * 1 + 0);
	},
	function ($x, $y)
	{
		return variations\swirl($x * .5 + $y * 0 + 1, $x * 0 + $y * .5 + 1);
	}
];
$flame1Weights = [0.5, 1];

$iterations = 20000000;
$imageSize = 2024 / 2;

$iterationsPerStep = $iterations / 100;
$iterationStep = 0;
$progress = 0;

$zoom = 0.3;

$currentFlame = $flame1;
$currentWeight = $flame1Weights;

$image = imagecreatetruecolor($imageSize, $imageSize);

$x = rand(-$imageSize/2, $imageSize/2) / $imageSize;
$y = rand(-$imageSize/2, $imageSize/2) / $imageSize;
for ($i = 0; $i < $iterations; $i++)
{
	$rand = rand(0, 100) / 100;

	for ($f = 0; $f < count($currentFlame); $f++)
	{
		if ($rand <= $currentWeight[$f])
		{
			$randomFunction = $currentFlame[$f];
			break;
		}
	}

	list($x, $y) = $randomFunction($x, $y);

	$xMapped = $x * $imageSize/2 * $zoom + $imageSize/2;
	$yMapped = $y * $imageSize/2 * $zoom + $imageSize/2;

	$col = imagecolorat($image, $xMapped, $imageSize - $yMapped) + 1;
	imagesetpixel($image, $xMapped, $imageSize - $yMapped, $col);

	if( ++$iterationStep > $iterationsPerStep )
	{
		$iterationStep = 0;
		$progress += 1;
		echo "progress:$progress\n";
	}
}

// cloloring
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

	for ($i = 0; $i < $imageSize; $i++)
	{
		for ($j = 0; $j < $imageSize; $j++)
		{
			$col = imagecolorat($image, $i, $j);

			$value = (int)(0xff * (log($col) / $max));

			$newCol = $value;
			$newCol += $value << 8;
			$newCol += $value << 16;

			imagesetpixel($image, $i, $j, $newCol);
		}
	}
}

imagepng($image, "output.png", 6);
