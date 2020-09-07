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
			->setDescription('sets the output path/name default:"./output"')
			->setValidation(function ($value)
			{
				return is_numeric($value) && intval($value) >= 1;
			})
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

	return $getOpt->getOptions();
}