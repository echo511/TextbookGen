<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Plugin\Image;

use Echo511\TextbookGen\ISnippet;
use Echo511\TextbookGen\Plugin\Metadata\Analyzator;
use Echo511\TextbookGen\Plugin\Metadata\Manipulator;
use Echo511\TextbookGen\Plugin\RelationMap\RelationMap;
use Nette\Object;


/**
 * Include <img> based on content.
 */
class ImagePlugin extends Object
{

	/** @var Analyzator */
	private $analyzator;

	/** @var Manipulator */
	private $manipulator;

	/** @var RelationMap */
	private $relationMap;

	/** @var ImageIndex */
	private $imageIndex;


	/**
	 * @param Analyzator $analyzator
	 * @param Manipulator $manipulator
	 * @param RelationMap $relationMap
	 * @param ImageIndex $imageIndex
	 */
	public function __construct(Analyzator $analyzator, Manipulator $manipulator, RelationMap $relationMap, ImageIndex $imageIndex)
	{
		$this->analyzator = $analyzator;
		$this->manipulator = $manipulator;
		$this->relationMap = $relationMap;
		$this->imageIndex = $imageIndex;
	}



	/**
	 * Process content.
	 * @param string $content
	 * @param ISnippet $snippet
	 * @return string
	 */
	public function process($content, ISnippet $snippet)
	{
		foreach ($this->analyzator->getAttributes('image', $snippet) as $name) {
			$link = $this->imageIndex->getUrlByName($name);
			$for = '<a target="_blank" href="' . $link . '"><img src="' . $link . '" /></a>';
			$content = $this->manipulator->replaceAttributes('image', $name, $for, $content);
		}
		return $content;
	}



}