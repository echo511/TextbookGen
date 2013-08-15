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

use Echo511\TextbookGen\IOutputGeneratorFactory;
use Echo511\TextbookGen\ISnippet;
use Echo511\TextbookGen\Plugin\Metadata\Manipulator;
use Echo511\TextbookGen\Plugin\RelationMap\RelationMap;
use Nette\Object;


/**
 * Handles including snippets.
 */
class Includer extends Object
{

	/** @var IncludedSnippetsTracker */
	private $includedSnippetsTracker;

	/** @var IOutputGeneratorFactory */
	private $outputGeneratorFactory;

	/** @var RelationMap */
	private $relationMap;

	/** @var Manipulator */
	private $manipulator;


	/**
	 * @param IncludedSnippetsTracker $includedSnippetsTracker
	 * @param IOutputGeneratorFactory $outputGeneratorFactory
	 * @param RelationMap $relationMap
	 */
	public function __construct(Manipulator $manipulator, IncludedSnippetsTracker $includedSnippetsTracker, IOutputGeneratorFactory $outputGeneratorFactory, RelationMap $relationMap)
	{
		$this->manipulator = $manipulator;
		$this->includedSnippetsTracker = $includedSnippetsTracker;
		$this->outputGeneratorFactory = $outputGeneratorFactory;
		$this->relationMap = $relationMap;
	}



	/**
	 * Run filter.
	 * @param string $content
	 * @param ISnippet $snippet
	 * @return string
	 */
	public function process($content, ISnippet $snippet)
	{
		$content = $this->performInclude($content, $snippet);
		return $content;
	}



	/**
	 * Do include
	 * @param string $content
	 * @param ISnippet $snippet
	 * @return string
	 */
	public function performInclude($content, ISnippet $snippet)
	{
		$this->includedSnippetsTracker->markAsIncluded($snippet);
		$depth = $this->includedSnippetsTracker->getDepthBySnippet($snippet);
		foreach ($this->relationMap->getReferenced($snippet) as $referenced) {
			$this->includedSnippetsTracker->markDepth($referenced, $depth + 1);
			$outputGenerator = $this->outputGeneratorFactory->create($referenced);

			$value = $this->relationMap->getNameOfSnippet($referenced);
			$for = $outputGenerator->generate();
			$content = $this->manipulator->replaceAttributes('include', $value, $for, $content);
			$this->includedSnippetsTracker->markAsIncluded($referenced);
		}
		return $content;
	}



}