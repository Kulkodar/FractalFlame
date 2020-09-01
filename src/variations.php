<?php

namespace FractalFlame\variations;

// $r = sqrt($x * $x + $y * $y);
// $theta = atan( $x / $y );
// $phi = atan( $y / $x );
// $omega = 0 or pi()
// $Alpha = 1 or -1
// $Phi = [0.1]

function linear($x, $y)
{
	return [$x, $y];
}

function sinusoidal($x, $y)
{
	return [sin($x), sin($y)];
}

function spherical($x, $y)
{
	$r = ($x * $x + $y * $y);
	return [$x / $r, $y / $r];
}

function swirl($x, $y)
{
	$r = $x * $x + $y * $y;
	$c1 = sin($r);
	$c2 = cos($r);
	return [$x * $c1 - $y * $c2, $x * $c2 + $y * $c1];
}

function swirlInverted($x, $y)
{
	$r = $x * $x + $y * $y;
	$c1 = sin(-$r);
	$c2 = cos(-$r);
	return [$x * $c1 - $y * $c2, $x * $c2 + $y * $c1];
}

function horseshoe($x, $y)
{
	$r = 1 / sqrt($x * $x + $y * $y);

	return [$r * ($x * $x - $y * $y), $r * 2 * $x * $y];
}

function polar($x, $y)
{
	$nx = atan2($x, $y) / pi();
	$ny = sqrt($x ** 2 + $y ** 2) - 1;
	return [$nx, $ny];
}

function polarInverted($x, $y)
{
	$nx = atan2($x, -$y) / pi();
	$ny = sqrt($x ** 2 + $y ** 2) - 1;
	return [$nx, -$ny];
}

function handkerchief($x, $y)
{
	$theta = atan2($x, $y);
	$r = sqrt($x * $x + $y * $y);

	return [$r * sin($theta + $r), $r * cos($theta - $r)];
}

function handkerchiefApophysis($x, $y)
{
	$theta = atan2($x, $y);
	$r = sqrt($x * $x + $y * $y);

	return [$r * sin($theta - $r), ($r * cos($theta + $r))];
}

function heart($x, $y)
{
	$r = sqrt($x * $x + $y * $y);
	$theta = atan2($x, $y) * $r;

	return [$r * sin($theta), $r * -cos($theta)];
}

function heartInverted($x, $y)
{
	$r = sqrt($x * $x + $y * $y);
	$theta = atan2($x, -$y) * $r;

	return [$r * sin($theta), -($r * -cos($theta))];
}

function disc($x, $y)
{
	$a = atan2($x, $y) / pi();
	$r = sqrt($x * $x + $y * $y) * pi();

	return [sin($r) * $a, cos($r) * $a];
}

function discInverted($x, $y)
{
	$a = atan2($x, $y) / pi();
	$r = sqrt($x * $x + $y * $y) * pi();

	return [sin($r) * $a, -(cos($r) * $a)];
}

function spiral($x, $y)
{
	$theta = atan2($x, $y);
	$r = sqrt($x * $x + $y * $y);

	return [(cos($theta) + sin($r)) * (1 / $r), (sin($theta) - cos($r)) * (1 / $r)];
}

function spiralInverted($x, $y)
{
	$theta = atan2($x, -$y);
	$r = sqrt($x * $x + $y * $y);

	return [(cos($theta) + sin($r)) * (1 / $r), -(sin($theta) - cos($r)) * (1 / $r)];
}

function hyperbolic($x, $y)
{
	$theta = atan2($x, $y);
	$r = sqrt($x * $x + $y * $y);
	return [sin($theta) / $r, $r * cos($theta)];
}

function diamond($x, $y)
{
	$theta = atan2($x, $y);
	$r = sqrt($x * $x + $y * $y);
	return [sin($theta) * cos($r), cos($theta) * sin($r)];
}

function ex($x, $y)
{
	$a = atan2($x, $y);
	$r = sqrt($x * $x + $y * $y);
	$n0 = sin($a + $r);
	$n1 = cos($a - $r);
	$m0 = $n0 * $n0 * $n0 * $r;
	$m1 = $n1 * $n1 * $n1 * $r;
	return [($m0 + $m1), ($m0 - $m1)];
}

function exInverted($x, $y)
{
	$a = -atan2($x, $y);
	$r = sqrt($x * $x + $y * $y);
	$n0 = -sin($a + $r);
	$n1 = -cos($a - $r);
	$m0 = $n0 * $n0 * $n0 * $r;
	$m1 = $n1 * $n1 * $n1 * $r;
	return [($m0 + $m1), -($m0 - $m1)];
}

function julia($x, $y)
{

	$random = rand(0, 1) * pi();
	$r = sqrt(sqrt($x * $x + $y * $y));
	$theta = atan2($x, $y) / 2 + $random;
	// $phi = atan( $y / $x );
	// $omega = 0 or pi()
	// $Alpha = 1 or -1
	// $Phi = [0.1]
	return [$r * cos($theta), $r * sin($theta)];
}

function juliaRotated($x, $y)
{

	$random = rand(0, 1) * pi();
	$r = sqrt(sqrt($x * $x + $y * $y));
	$theta = atan2($x, $y) / 2 + $random;
	// $phi = atan( $y / $x );
	// $omega = 0 or pi()
	// $Alpha = 1 or -1
	// $Phi = [0.1]
	return [$r * sin($theta), -$r * cos($theta)];
}

function bent($x, $y)
{
	$nx = $x;
	$ny = $y;

	if ($nx < 0.0)
		$nx = $nx * 2.0;
	if ($ny < 0.0)
		$ny = $ny / 2.0;
	return [$nx, $ny];
}

function bentInverted($x, $y)
{
	$nx = $x;
	$ny = $y;

	if ($nx < 0.0)
		$nx = $nx * 2.0;
	if ($ny > 0.0)
		$ny = $ny / 2.0;
	return [$nx, $ny];
}

function waves($x, $y, $b, $c, $e, $f)
{
	$sin = sin($c == 0 ? 0 : $y / ($c * $c));
	$sin1 = sin($f == 0 ? 0 : $x / ($f * $f));

	return [$x + $b * $sin, $y + $e * $sin1];
}

function wavesInverted($x, $y, $b, $c, $e, $f)
{
	$sin = -sin($c == 0 ? 0 : $y / ($c * $c));
	$sin1 = -sin($f == 0 ? 0 : $x / ($f * $f));

	return [$x + $b * $sin, $y + $e * $sin1];
}

function fisheye($x, $y)
{
	$r = 2.0 / (sqrt($x * $x + $y * $y) + 1);
	return [($r * $y), ($r * $x)];
}

function fisheyeInverted($x, $y)
{
	$r = 2.0 / (sqrt($x * $x + $y * $y) + 1);
	return [-($r * $y), -($r * $x)];
}

function popcorn($x, $y, $c, $f)
{
	return [$x + $c * sin(tan(3 * $y)), $y + $f * sin(tan(3 * $x))];
}

function exponential($x, $y)
{
	$exp = exp($x - 1);
	return [$exp * cos(pi() * $y), $exp * sin(pi() * $y)];
}

function power($x, $y)
{
	$theta = atan2($x, $y);
	$r = pow(sqrt($x * $x + $y * $y), sin($theta));
	return [$r * cos($theta), $r * sin($theta)];
}

function powerInverted($x, $y)
{
	$theta = atan2($x, $y);
	$r = pow(sqrt($x * $x + $y * $y), sin($theta));
	return [-($r * cos($theta)), -($r * sin($theta))];
}

function cosine($x, $y)
{
	return [cos(pi() * $x) * cosh($y), -sin(pi() * $x) * sinh($y)];
}

function custom1($x, $y)
{
	$r = ($x * $x + $y * $y);
	$c1 = cos($r);
	$c2 = sin($r);
	return [$x * $c1 - $y * $c2, $x * $c2 + $y * $c1];
}