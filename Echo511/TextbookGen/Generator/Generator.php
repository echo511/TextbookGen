<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Generator;

use Echo511\TextbookGen\IGenerator;
use Echo511\TextbookGen\IIndex;
use Echo511\TextbookGen\IOutputGenerator;
use Echo511\TextbookGen\IOutputGeneratorFactory;
use Echo511\TextbookGen\IOutputStorage;
use Nette\Object;


/**
 * Envelope over entire generation process.
 */
class Generator extends Object implements IGenerator
{

	/** @var callable */
	public $onStartup;

	/** @var callable */
	public $onShutdown;

	/** @var IIndex */
	private $index;

	/** @var IOutputGeneratorFactory */
	private $outputGeneratorFactory;

	/** @var IOutputStorage */
	private $outputStorage;


	/**
	 * @param IIndex $index
	 * @param IOutputGenerator $outputGenerator
	 * @param IOutputStorage $outputStorage
	 */
	public function __construct(IIndex $index, IOutputGeneratorFactory $outputGeneratorFactory, IOutputStorage $outputStorage)
	{
		$this->index = $index;
		$this->outputGeneratorFactory = $outputGeneratorFactory;
		$this->outputStorage = $outputStorage;
	}



	/* ---------- IGenerator ---------- */

	public function run()
	{
		// Life cycle
		$this->startup();
		$this->generate();
		$this->shutdown();
	}



	/* ---------- Additions ---------- */

	/**
	 * Startup.
	 */
	protected function startup()
	{
		$this->onStartup($this->index);
	}



	/**
	 * Perform generation.
	 */
	protected function generate()
	{
		foreach ($this->index->getAll() as $snippet) {
			$output = $this->outputGeneratorFactory->create($snippet)->generate();
			$this->outputStorage->store($output, $snippet);
		}
	}



	/**
	 * Shutdown.
	 */
	protected function shutdown()
	{
		$this->onShutdown($this->index);
	}



}