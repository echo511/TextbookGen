<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Index;


/**
 * Basic implementation of IIndex.
 */
class Index extends \Nette\Object implements IIndex
{

	/** @var ISnippets[] */
	private $snippets = array();

	/** @var array */
	private $tags = array();


	/* ---------- IIndex ---------- */

	public function add(ISnippet $snippet)
	{
		$this->snippets[$snippet->getHash()] = $snippet;
		foreach ($snippet->getTags() as $tag => $param) {
			$this->tags[$tag][$param][] = $snippet;
		}
	}



	public function get($hash)
	{
		return $this->snippets[$hash];
	}



	public function getByTag($tag, $param = null)
	{
		return $this->tags[$tag][$param];
	}



	public function getAll()
	{
		return $this->snippets;
	}



}