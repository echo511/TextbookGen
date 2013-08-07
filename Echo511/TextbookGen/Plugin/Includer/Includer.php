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
use Echo511\TextbookGen\OutputGenerator\FileTemplateOutputGeneratorFactory;
use Nette\Object;


/**
 * Handles including snippets.
 */
class Includer extends Object
{

	/** @var IncludedSnippetsTracker */
	private $includedSnippetsTracker;

	/** @var FileTemplateOutputGeneratorFactory */
	private $outputGeneratorFactory;

	/** @var RelationMap */
	private $relationMap;


	/**
	 * @param IncludedSnippetsTracker $includedSnippetsTracker
	 * @param FileTemplateOutputGeneratorFactory $outputGeneratorFactory
	 * @param RelationMap $relationMap
	 */
	public function __construct(IncludedSnippetsTracker $includedSnippetsTracker, FileTemplateOutputGeneratorFactory $outputGeneratorFactory, RelationMap $relationMap)
	{
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
		$content = $this->removeMetadata($content, $snippet);
		$content = $this->performInclude($content, $snippet);
		return $content;
	}



	/**
	 * Remove variables from content.
	 * @param type $content
	 * @param ISnippet $snippet
	 * @return string
	 */
	public function removeMetadata($content, ISnippet $snippet)
	{
		$ref = $this->relationMap->getRefBySnippet($snippet);

		if ($ref) {
			$what = '@includer.ref:' . $ref;
			return str_replace($what, '', $content);
		}

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

			$what = '@include:' . $this->relationMap->getRefBySnippet($referenced);
			$for = $outputGenerator->generate();
			$content = str_replace($what, $for, $content);
			$this->includedSnippetsTracker->markAsIncluded($referenced);
		}
		return $content;
	}



}