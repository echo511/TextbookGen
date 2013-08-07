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
 * Interface for output storage.
 */
interface IOutputStorage
{

	/**
	 * Store generated snippet's output.
	 * @param string $output Current snippets output - parsed by filters etc.
	 * @param ISnippet $snippet
	 */
	function store($output, ISnippet $snippet);

}