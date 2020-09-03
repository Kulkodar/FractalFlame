<?php

namespace FractalFlame;

/**
 * A color palette is array of 256 triples of bytes (RGB).
 */
class ColorPalette
{
	/**
	 * Loads a palette from a palette file.
	 *
	 * Format:
	 * The bytes of a triplet are stored as decimal strings separated by space
	 * and a new line after the last byte of the triplet.
	 * eg.
	 * 166 148 122
	 * 219 162 132
	 * ...
	 *
	 * @param string $filePath path to the palette.
	 */
	public function __construct(string $filePath)
	{
		if ($file = fopen($filePath, "r"))
		{
			while (!feof($file))
			{
				$rgbValues = fgets($file);
				$this->color[] = str_word_count($rgbValues, 1, "0123456789");;
			}
			fclose($file);
		}
	}

	public $color = [];
}