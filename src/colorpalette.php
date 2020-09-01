<?php

class ColorPalette
{
	public function __construct($filePath)
	{
		if ($file = fopen($filePath, "r"))
		{
			while (!feof($file))
			{
				$rgbValues = fgets($file);
				$this->color[] = str_word_count ( $rgbValues, 1 , "0123456789");;
			}
			fclose($file);
		}
//		print_r($color);
	}

	public $color = [];
}