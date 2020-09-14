<?php

namespace FractalFlame;

use GetOpt\ArgumentException;
use GetOpt\ArgumentException\Missing;
use GetOpt\GetOpt;
use GetOpt\Option;

/**
 * Checks the arguments and returns an associative array with all set options.
 * @return array associative array with all set options.
 */
function checkArguments() : array
{
	$getOpt = new GetOpt();

	$getOpt->addOptions([

		Option::create('h', 'help', GetOpt::NO_ARGUMENT)
			->setDescription('Show this help and quit'),

		Option::create('f', "flame", GetOpt::REQUIRED_ARGUMENT)
			->setDescription('The flame preset to load default:"CustomFlame1"')
			->setValidation(function ($value)
			{
				return is_callable(["FractalFlame\\flames\\$value", "Create"]);
			}),

		Option::create('c', "colorPalette", GetOpt::REQUIRED_ARGUMENT)
			->setDescription('The color palette to use default:"sky-flesh"')
			->setValidation(function ($value)
			{
				return file_exists(__DIR__ . "/colorPalettes/" . $value);
			}),

		Option::create('i', "iterations", GetOpt::REQUIRED_ARGUMENT)
			->setDescription('The number of iterations to generate the picture default:1000000')
			->setValidation(function ($value)
			{
				return is_numeric($value) && intval($value) >= 0;
			}),

		Option::create('s', "imageSize", GetOpt::REQUIRED_ARGUMENT)
			->setDescription('The size of the image default:2024')
			->setValidation(function ($value)
			{
				return is_numeric($value) && intval($value) >= 0;
			}),

		Option::create('z', "zoom", GetOpt::REQUIRED_ARGUMENT)
			->setDescription('Option to zoom default: 1')
			->setValidation(function ($value)
			{
				return is_numeric($value) && floatval($value) >= 0;
			}),

		Option::create('g', "gamma", GetOpt::REQUIRED_ARGUMENT)
			->setDescription('sets the gamma default: 4')
			->setValidation(function ($value)
			{
				return is_numeric($value) && intval($value) >= 1;
			}),

		Option::create('o', null, GetOpt::REQUIRED_ARGUMENT)
			->setDescription('sets the output path/name default:"./output"'),

		Option::create(null, "xOffset", GetOpt::REQUIRED_ARGUMENT)
			->setDescription('Option to offset the flame default: 0')
			->setValidation(function ($value)
			{
				return is_numeric($value);
			}),

		Option::create(null, "yOffset", GetOpt::REQUIRED_ARGUMENT)
			->setDescription('Option to offset the flame default: 0')
			->setValidation(function ($value)
			{
				return is_numeric($value);
			}),

		Option::create(null, "superSampling", GetOpt::REQUIRED_ARGUMENT)
			->setDescription('Option set super sampling default: 1')
			->setValidation(function ($value)
			{
				return is_numeric($value) && intval($value) > 0;
			}),

		Option::create(null, "listColorPalettes", GetOpt::NO_ARGUMENT)
			->setDescription('Lists all available color palettes'),

		Option::create(null, "listFlames", GetOpt::NO_ARGUMENT)
			->setDescription('Lists all available flames')
	]);

	// process arguments and catch user errors
	try
	{
		try
		{
			$getOpt->process();
		}
		catch (Missing $exception)
		{
			// catch missing exceptions if help is requested
			if (!$getOpt->getOption('help'))
				throw $exception;
		}
	}
	catch (ArgumentException $exception)
	{
		echo PHP_EOL . $getOpt->getHelpText();
		exit;
	}

	if($getOpt->getOption("listColorPalettes"))
	{
		echo "Available Color Palettes:\n";

		foreach ($files = glob(__DIR__ . "/colorPalettes/*") as $file)
		{
			$basename = basename($file, "");
			echo $basename . "\n";
		}

		die;
	}

	if($getOpt->getOption("listFlames"))
	{
		echo "Available Flames:\n";

		foreach ($files = glob(__DIR__ . "/flames/*") as $file)
		{
			$basename = basename($file, ".php");
			echo $basename . "\n";
		}

		die;
	}

	return $getOpt->getOptions();
}