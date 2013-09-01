<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Composer;


/**
 * Scripts run by composer.
 */
class Scripts
{

	/**
	 * Create dirs after create-project command.
	 */
	public static function postCreateProjectCmd()
	{
		$root = __DIR__ . '/../../..';

		$dirs = array(
		    $root . '/log',
		    $root . '/temp',
		    $root . '/example/output',
		);

		foreach ($dirs as $dir) {
			if (!is_dir($dir)) {
				mkdir($dir);
			}
		}
	}



}