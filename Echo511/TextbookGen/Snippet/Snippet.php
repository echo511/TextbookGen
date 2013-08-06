<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Snippet;

use Echo511\TextbookGen\ISnippet;
use Nette\Object;


/**
 * Interface for factory of Snippet.
 */
interface ISnippetFactory
{

	/** @return Snippet */
	function create($content);

}


/**
 * Basic implementation of ISnippet.
 */
class Snippet extends Object implements ISnippet
{

	/** @var string */
	private $content;

	/** @var string */
	private $hash;

	/** @var array */
	private $tags = array();


	/**
	 * @param string $content
	 */
	public function __construct($content)
	{
		$this->content = $content;
		$this->hash = self::hash($this);
	}



	/* ---------- ISnippet ---------- */

	public function getHash()
	{
		return $this->hash;
	}



	public function getTags()
	{
		return $this->tags;
	}



	public function getContent()
	{
		return $this->content;
	}



	/* ---------- Additions ---------- */

	/**
	 * Add tag to current snippet.
	 * @param string $tag
	 * @param mixed $param
	 */
	protected function addTag($tag, $param = null)
	{
		$this->tags[$tag] = $param;
	}



	/* ---------- Helper ---------- */

	public static function hash(ISnippet $snippet)
	{
		return sha1($snippet->getContent());
	}



}