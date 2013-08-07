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
 * Interface for creating output generator.
 */
interface IOutputGeneratorFactory
{

	/**
	 * Create output generator.
	 * @param ISnippet $snippet
	 * @return IOutputGenerator
	 */
	function create(ISnippet $snippet);

}