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
 * Snippet of information. / For example: stomach or heart attack
 */
interface ISnippet
{

	/**
	 * Get sha1 hash based on content.
	 * @return string
	 */
	function getHash();

	/**
	 * Return tags for this snippet.
	 * @return array()
	 */
	function getTags();

	/**
	 * Return raw content of snippet.
	 * @return string
	 */
	function getContent();

}