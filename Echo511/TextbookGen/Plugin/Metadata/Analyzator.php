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
		$matches = $this->matchAll($name, $snippet);
		if (isset($matches[0])) {
			return $matches[0];
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
		return $this->matchAll($name, $snippet);
	}



	private function matchAll($name, ISnippet $snippet)
	{
		$content = $snippet->getContent();

		$pattern = '/@' . $name . ' ([^"\[\]\n\r]*[^*\[\]\s])/';
		preg_match_all($pattern, $content, $matches_1);

		$pattern = '/@' . $name . ':"([^@]*)"/';
		preg_match_all($pattern, $content, $matches_2);

		$pattern = '/@' . $name . ':([^"\s][^"\s]*)/';
		preg_match_all($pattern, $content, $matches_3);

		return array_merge($matches_1[1], $matches_2[1], $matches_3[1]);
	}



}