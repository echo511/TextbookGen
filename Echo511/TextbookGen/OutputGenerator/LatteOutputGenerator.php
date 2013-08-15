<?php

namespace Echo511\TextbookGen\OutputGenerator;

use Echo511\TextbookGen\IOutputGenerator;
use Echo511\TextbookGen\ISnippet;
use Nette\Object;
use Nette\Templating\ITemplate;


class LatteOutputGenerator extends Object implements IOutputGenerator
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