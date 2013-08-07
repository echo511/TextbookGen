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

use Echo511\TextbookGen\Generator\Generator;
use Echo511\TextbookGen\IIndex;
use Echo511\TextbookGen\OutputGenerator\FileTemplateOutputGenerator;
use Echo511\TextbookGen\OutputGenerator\FileTemplateOutputGeneratorFactory;
use Nette\Templating\Template;


/**
 * Registers filter.
 */
class Registrator
{

	/**
	 * Register filter.
	 * @param FileTemplateOutputGeneratorFactory $outputGeneratorFactory
	 * @param Generator $generator
	 * @param IIndex $index
	 */
	public static function register(FileTemplateOutputGeneratorFactory $outputGeneratorFactory, Generator $generator, IIndex $index)
	{
		$analyzator = new Analyzator;
		$includedSnippetsTracker = new IncludedSnippetsTracker;
		$relationMap = new RelationMap($analyzator, $index);
		$includer = new Includer($includedSnippetsTracker, $outputGeneratorFactory, $relationMap);

		$generator->onStartup[] = callback($relationMap, 'createRelationMap');
		$generator->onBeforeSnippetGenerate[] = callback($includedSnippetsTracker, 'markAsFirst');
		$generator->onAfterSnippetGenerate[] = callback($includedSnippetsTracker, 'reset');

		// Provide services
		$outputGeneratorFactory->onCreate[] = function (FileTemplateOutputGenerator $outputGenerator) use ($includer, $includedSnippetsTracker, $relationMap) {
				$outputGenerator->onCreateTemplate[] = function (Template $template) use ($includer, $includedSnippetsTracker, $relationMap) {
						$template->includer = $includer;
						$template->includedSnippetsTracker = $includedSnippetsTracker;
						$template->includerRelationMap = $relationMap;
					};
			};
	}



}