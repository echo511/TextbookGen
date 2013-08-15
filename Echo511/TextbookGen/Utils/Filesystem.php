<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Utils;


/**
 * Perform filesystem operations.
 */
final class Filesystem
{

	public function __construct()
	{
		throw new \Exception("Static class " . get_called_class() . " cannot be instantized.");
	}



	/**
	 * Copy directory recursively.
	 * http://stackoverflow.com/questions/5707806/recursive-copy-of-directory
	 * @param string $source
	 * @param string $dest
	 * @return boolean
	 */
	public static function copy($source, $dest)
	{
		// Simple copy for a file
		if (is_file($source)) {
			return copy($source, $dest);
		}

		// Make destination directory
		if (!is_dir($dest)) {
			mkdir($dest);
		}

		// Loop through the folder
		$dir = dir($source);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}

			// Deep copy directories
			if ($dest !== "$source/$entry") {
				self::copy("$source/$entry", "$dest/$entry");
			}
		}

		// Clean up
		$dir->close();
		return true;
	}



}