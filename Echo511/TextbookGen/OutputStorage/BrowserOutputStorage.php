<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\OutputStorage;

use Echo511\TextbookGen\ISnippet;


/**
 * Display output in browser.
 */
final class BrowserOutputStorage extends OutputStorage implements \Echo511\TextbookGen\Plugin\Link\ILinkProvider
{

	/* ---------- OutputStorage ---------- */

	public function doStore($output, ISnippet $snippet)
	{
		echo $output;
	}



	public function getLinkForSnippet(ISnippet $snippet)
	{
		return $snippet->hash;
	}



}