<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\OutputGenerator;

use Echo511\TextbookGen\IOutputGenerator;
use Echo511\TextbookGen\IOutputGeneratorFactory;
use Echo511\TextbookGen\ISnippet;
use Exception;
use Nette\Caching\Storages\PhpFileStorage as ICacheStorage;
use Nette\Latte\Engine as LatteEngine;
use Nette\Object;
use Nette\Templating\FileTemplate;


/**
 * Create instance of FileTemplateOutputGenerator
 */
class FileTemplateOutputGeneratorFactory extends Object implements IOutputGeneratorFactory
{

	/** @var callable Use to modify generator after creation. */
	public $onCreate;

	/** @var string */
	private $templateFilename;

	/** @var ICacheStorage */
	private $cacheStorage;


	/**
	 * @param string $templateFilename
	 * @param ICacheStorage $cacheStorage
	 */
	public function __construct($templateFilename, ICacheStorage $cacheStorage)
	{
		$this->templateFilename = $templateFilename;
		$this->cacheStorage = $cacheStorage;
	}



	/**
	 * Create generator instance.
	 * @param ISnippet $snippet
	 * @return FileTemplateOutputGenerator
	 */
	function create(ISnippet $snippet)
	{
		$gen = new FileTemplateOutputGenerator($snippet, $this->templateFilename, $this->cacheStorage);
		$this->onCreate($gen);
		return $gen;
	}



}


/**
 * Generate snippet's output with Latte template.
 */
class FileTemplateOutputGenerator extends Object implements IOutputGenerator
{

	/** @var callable Use to modify template after creation. */
	public $onCreateTemplate;

	/** @var ISnippet */
	private $snippet;

	/** @var string */
	private $templateFilename;

	/** @var ICacheStorage */
	private $cacheStorage;


	/**
	 * @param ISnippet $snippet
	 * @param string $templateFilename
	 * @param ICacheStorage $cacheStorage
	 */
	public function __construct(ISnippet $snippet, $templateFilename, ICacheStorage $cacheStorage)
	{
		$this->snippet = $snippet;
		$this->setTemplateFilename($templateFilename);
		$this->cacheStorage = $cacheStorage;
	}



	/* ---------- IOutputGenerator ---------- */

	public function generate()
	{
		// Create template
		$template = $this->createTemplate();

		// Pass snippet
		$template->snippet = $this->snippet;

		// Render
		$output = (string) $template;
		return $output;
	}



	/* ---------- Additions ---------- */

	/**
	 * Set path to template file.
	 * @param string $filename
	 * @throws Exception
	 */
	public function setTemplateFilename($filename)
	{
		if (!file_exists($filename)) {
			throw new Exception("Template not found at $filename.");
		} else {
			$this->templateFilename = $filename;
		}
	}



	/**
	 * Create template for snippet.
	 * @param FileTemplate $template
	 * @return FileTemplate
	 * @throws Exception
	 */
	protected function createTemplate()
	{
		if (!isset($this->templateFilename)) {
			throw new Exception("Cannot proceed. Template file not setted.");
		}

		// Create template and register basics
		$template = new FileTemplate($this->templateFilename);
		$template->setCacheStorage($this->cacheStorage);
		$template->registerHelperLoader('Nette\Templating\Helpers::loader');
		$template->onPrepareFilters[] = function($template) {
				$template->registerFilter(new LatteEngine);
			};

		// Callback for others to pass some variables, services etc.
		$this->onCreateTemplate($template);
		return $template;
	}



}