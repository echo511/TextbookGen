<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen;


/**
 * Snipped with unique title. Unlike hash however - human readable.
 */
interface ITitledSnippet
{

	/**
	 * Return snippet's title. Title should be unique.
	 * @return string
	 */
	function getTitle();

}