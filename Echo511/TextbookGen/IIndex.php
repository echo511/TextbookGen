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
 * Index of all snippets in current job.
 */
interface IIndex
{

	/**
	 * Add snippet into index.
	 * @param ISnippet $snippet
	 */
	function add(ISnippet $snippet);

	/**
	 * Return single snippet based on some hash. For example hash of full filepath.
	 * @param string $hash
	 * @return ISnippet|bool 
	 */
	function get($hash);

	/**
	 * Return snippets tagged by tag.
	 * @param string $tag
	 * @param mixed $param Tag's value
	 * @return ISnippet[]
	 */
	function getByTag($tag, $param = null);

	/**
	 * Return all snippets in index.
	 * @return ISnippet[]
	 */
	function getAll();

}