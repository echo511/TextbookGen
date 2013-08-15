<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Plugin\Link;

use Echo511\TextbookGen\ISnippet;


/**
 * Provide URL for snippets.
 */
interface ILinkProvider
{

	/**
	 * Return URL for snippet.
	 * @param ISnippet $snippet
	 * @return string
	 */
	function getLinkForSnippet(ISnippet $snippet);

}