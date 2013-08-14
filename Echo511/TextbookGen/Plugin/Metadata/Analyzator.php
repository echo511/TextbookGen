<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Plugin\Metadata;

use Echo511\TextbookGen\ISnippet;
use Nette\Object;


/**
 * Parse content and grab attributes.
 * Atribute in text: @name value value_continues
 */
class Analyzator extends Object
{

	/**
	 * Return single attribute's value or false.
	 * @param string $name
	 * @param ISnippet $snippet
	 * @return string|bool
	 */
	public function getAttribute($name, ISnippet $snippet)
	{
		$pattern = '/@' . $name . ' (.*[^\s])/';
		$content = $snippet->getContent();
		preg_match($pattern, $content, $matches);

		if (isset($matches[1])) {
			return $matches[1];
		}
		return false;
	}



	/**
	 * Return attributes' values or false.
	 * @param string $name
	 * @param ISnippet $snippet
	 * @return array|bool
	 */
	public function getAttributes($name, ISnippet $snippet)
	{
		$pattern = '/@' . $name . ' (.*[^\s])/';
		$content = $snippet->getContent();
		preg_match_all($pattern, $content, $matches);

		if (isset($matches[1])) {
			return $matches[1];
		}
		return false;
	}



}