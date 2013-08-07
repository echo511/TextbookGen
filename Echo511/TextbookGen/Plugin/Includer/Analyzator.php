<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Plugin\Includer;

use Echo511\TextbookGen\ISnippet;
use Nette\Object;


/**
 * Parse content for necessary variables.
 */
class Analyzator extends Object
{

	/**
	 * Return designation of snippet.
	 * @param ISnippet $snippet
	 * @return string
	 */
	public function analyzeReference(ISnippet $snippet)
	{
		$pattern = '/@includer.ref:([^\s]*)/';
		$content = $snippet->getContent();
		preg_match($pattern, $content, $matches);

		if (isset($matches[1])) {
			return $matches[1];
		}
	}



	/**
	 * Return include references to other snippets.
	 * @param ISnippet $snippet
	 * @return type
	 */
	public function analyzeReferenced(ISnippet $snippet)
	{
		$pattern = '/@include:([^\s]*)/';
		$content = $snippet->getContent();
		preg_match_all($pattern, $content, $matches);

		if (isset($matches[1])) {
			return $matches[1];
		}
	}



}