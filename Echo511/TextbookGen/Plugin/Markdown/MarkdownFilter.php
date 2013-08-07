<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Plugin\Markdown;

use Echo511\TextbookGen\OutputGenerator\FileTemplateOutputGenerator;
use Echo511\TextbookGen\OutputGenerator\FileTemplateOutputGeneratorFactory;
use Nette\Object;
use Nette\Templating\Template;


/**
 * Markdown filter.
 */
class MarkdownFilter extends Object
{

	/**
	 * Run filter.
	 * @param string $content
	 * @return string
	 */
	public function process($content)
	{
		require_once(__DIR__ . '/markdown.php');
		return Markdown($content);
	}



	/**
	 * Register filter.
	 * @param FileTemplateOutputGeneratorFactory $outputGeneratorFactory
	 */
	public static function register(FileTemplateOutputGeneratorFactory $outputGeneratorFactory)
	{
		$self = new self;

		// Provide services
		$outputGeneratorFactory->onCreate[] = function (FileTemplateOutputGenerator $outputGenerator) use ($self) {
				$outputGenerator->onCreateTemplate[] = function (Template $template) use ($self) {
						$template->markdownFilter = $self;
					};
			};
	}



}