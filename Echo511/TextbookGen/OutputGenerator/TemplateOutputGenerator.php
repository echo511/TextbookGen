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
use Echo511\TextbookGen\ISnippet;
use Nette\Object;
use Nette\Templating\ITemplate;


/**
 * Nette's templating system as output generator.
 */
class TemplateOutputGenerator extends Object implements IOutputGenerator
{

	/** @var ISnippet */
	private $snippet;

	/** @var ITemplate */
	private $template;


	public function __construct(ISnippet $snippet, ITemplate $template)
	{
		$this->snippet = $snippet;
		$this->template = $template;
	}



	public function generate()
	{
		$this->template->snippet = $this->snippet;
		return (string) $this->template;
	}



}