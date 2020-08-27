<?php

$sierpinskiGasket = [
	function ($x, $y)
	{
		return [$x * 0.5 + $y * 0 + 0, $x * 0 + $y * 0.5 + 0];
	},
	function ($x, $y)
	{
		return [$x * 0.5 + $y * 0 + 1, $x * 0 + $y * 0.5 + 0];
	},
	function ($x, $y)
	{
		return [$x * 0.5 + $y * 0 + 0, $x * 0 + $y * 0.5 + 1];
	}
];

$iterations = 200000;
$imageSize = 2024;

$image = imagecreatetruecolor($imageSize, $imageSize);

$x = rand(-$imageSize/2, $imageSize/2) / $imageSize;
$y = rand(-$imageSize/2, $imageSize/2) / $imageSize;
for ($i = 0; $i < $iterations; $i++)
{
	$randomFunction = $sierpinskiGasket[rand(0, count($sierpinskiGasket)-1)];
	list($x, $y) = $randomFunction($x, $y);

	$xMapped = $x * $imageSize/2 + $imageSize/2;
	$yMapped = $y * $imageSize/2 + $imageSize/2;

	imagesetpixel($image, $xMapped, $imageSize - $yMapped, 0xffffff);
}

imagepng($image, "output.png", 6);
