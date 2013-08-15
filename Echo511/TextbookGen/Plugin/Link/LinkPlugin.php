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
use Echo511\TextbookGen\Plugin\Metadata\Analyzator;
use Echo511\TextbookGen\Plugin\Metadata\Manipulator;
use Echo511\TextbookGen\Plugin\RelationMap\RelationMap;
use Nette\Object;


/**
 * Link from one snippet to another.
 */
class LinkPlugin extends Object
{

	/** @var Analyzator */
	private $analyzator;

	/** @var Manipulator */
	private $manipulator;

	/** @var RelationMap */
	private $relationMap;

	/** @var ILinkProvider */
	private $linkProvider;


	/**
	 * @param Analyzator $analyzator
	 * @param Manipulator $manipulator
	 * @param RelationMap $relationMap
	 * @param ILinkProvider $linkProvider
	 */
	public function __construct(Analyzator $analyzator, Manipulator $manipulator, RelationMap $relationMap, ILinkProvider $linkProvider)
	{
		$this->analyzator = $analyzator;
		$this->manipulator = $manipulator;
		$this->relationMap = $relationMap;
		$this->linkProvider = $linkProvider;
	}



	/**
	 * Process content.
	 * @param string $content
	 * @param ISnippet $snippet
	 * @return string
	 */
	public function process($content, ISnippet $snippet)
	{
		foreach ($this->analyzator->getAttributes('link', $snippet) as $name) {
			$linked = $this->relationMap->getSnippetByName($name);
			$link = $this->linkProvider->getLinkForSnippet($linked);
			$content = $this->manipulator->replaceAttributes('link', $name, $link, $content);
		}
		return $content;
	}



	/**
	 * Return URL for snippet.
	 * @param ISnippet $snippet
	 * @return string
	 */
	public function getLinkForSnippet(ISnippet $snippet)
	{
		return $this->linkProvider->getLinkForSnippet($snippet);
	}



}