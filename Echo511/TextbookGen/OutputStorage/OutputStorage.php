<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\OutputStorage;

use Echo511\TextbookGen\IOutputStorage;
use Echo511\TextbookGen\ISnippet;
use Nette\Object;


/**
 * Base for final implementation of IOutputStorage. Provides callbacks.
 */
abstract class OutputStorage extends Object implements IOutputStorage
{

	/** @var callable */
	public $onBeforeStore;

	/** @var callable */
	public $onSuccess;


	/* ---------- IOutputStorage ---------- */

	public function store($output, ISnippet $snippet)
	{
		$this->onBeforeStore($output, $snippet);
		$this->doStore($output, $snippet);
		$this->onSuccess($output, $snippet);
	}



	/* ---------- Additions ---------- */

	/**
	 * Perform snippet storing.
	 * @param string $output
	 * @param ISnippet $snippet
	 */
	abstract protected function doStore($output, ISnippet $snippet);

}