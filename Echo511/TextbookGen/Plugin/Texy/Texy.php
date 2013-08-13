<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Plugin;

use Echo511\TextbookGen\OutputGenerator\FileTemplateOutputGenerator;
use Echo511\TextbookGen\OutputGenerator\FileTemplateOutputGeneratorFactory;
use Nette\Templating\Template;
use Texy;


/**
 * Texy filter.
 */
class TexyPlugin
{

	/**
	 * Process content with texy.
	 * @param string $string
	 * @param int $headingTop
	 * @return string
	 */
	public function process($string, $headingTop = 1)
	{
		$texy = new Texy;
		$texy->headingModule->top = $headingTop;
		$texy->headingModule->generateID = TRUE;
		return $texy->process($string);
	}



	/**
	 * Register plugin.
	 * @param FileTemplateOutputGeneratorFactory $outputGeneratorFactory
	 */
	public static function register(FileTemplateOutputGeneratorFactory $outputGeneratorFactory)
	{
		$self = new self;

		// Provide services
		$outputGeneratorFactory->onCreate[] = function (FileTemplateOutputGenerator $outputGenerator) use ($self) {
				$outputGenerator->onCreateTemplate[] = function (Template $template) use ($self) {
						$template->texy = $self;
					};
			};
	}



}