<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\OutputGeneratorFactory;

use Echo511\TextbookGen\IOutputGeneratorFactory;
use Echo511\TextbookGen\ISnippet;
use Echo511\TextbookGen\OutputGenerator\TemplateOutputGenerator;
use Nette\DI\Container;
use Nette\Latte\Engine as Latte;
use Nette\Object;
use Nette\Templating\FileTemplate;


/**
 * Use Latte file.
 */
class FileTemplateLatteOutputGeneratorFactory extends Object implements IOutputGeneratorFactory
{

	/** @var string Location of latte file. */
	private $location;

	/** @var Container */
	private $container;

	/** @var callable Called after template is created. */
	public $onTemplate;


	/**
	 * @param string $location
	 * @param Container $container
	 */
	public function __construct($location, Container $container)
	{
		$this->location = $location;
		$this->container = $container;
	}



	/**
	 * Create output generator.
	 * @param ISnippet $snippet
	 * @param FileTemplate $template
	 * @return TemplateOutputGenerator
	 */
	public function create(ISnippet $snippet)
	{
		$template = new FileTemplate($this->location);
		$template->setCacheStorage($this->container->getService("nette.templateCacheStorage"));
		$template->registerHelperLoader('Nette\Templating\Helpers::loader');
		$template->onPrepareFilters[] = function($template) {
				$template->registerFilter(new Latte);
			};

		$this->onTemplate($template);
		return new TemplateOutputGenerator($snippet, $template);
	}



}