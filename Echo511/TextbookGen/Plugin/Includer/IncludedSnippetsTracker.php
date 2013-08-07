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
 * Tracks what snippets and where they were included into the generated one.
 */
class IncludedSnippetsTracker extends Object
{

	/** @var ISnippet */
	private $first;

	/** @var array */
	private $alreadyIncluded = array();

	/** @var array */
	private $depths = array();


	/** @var ISnippet */
	public function markAsFirst(ISnippet $snippet)
	{
		$this->first = $snippet;
		$this->markDepth($snippet, 0);
	}



	/**
	 * Mark snippet as included.
	 * @param ISnippet $snippet
	 */
	public function markAsIncluded(ISnippet $snippet)
	{
		$this->alreadyIncluded[$snippet->hash] = true;
	}



	/**
	 * Mark in what depth is snippet beeing included.
	 * @param ISnippet $snippet
	 * @param int $depth
	 */
	public function markDepth(ISnippet $snippet, $depth)
	{
		$this->depths[$snippet->hash] = $depth;
	}



	/**
	 * Is snippet the very first?
	 * @param ISnippet $snippet
	 * @return bool
	 */
	public function isFirst(ISnippet $snippet)
	{
		return $snippet === $this->first;
	}



	/**
	 * Was snippet already included?
	 * @param ISnippet $snippet
	 * @return bool
	 */
	public function isIncluded(ISnippet $snippet)
	{
		return isset($this->alreadyIncluded[$snippet->hash]);
	}



	/**
	 * Return depth snippet was included to.
	 * @param ISnippet $snippet
	 * @return int
	 */
	public function getDepthBySnippet(ISnippet $snippet)
	{
		return $this->depths[$snippet->hash];
	}



	/**
	 * Reset tracker before another snippet's generation.
	 */
	public function reset()
	{
		$this->first = null;
		$this->alreadyIncluded = array();
	}



}