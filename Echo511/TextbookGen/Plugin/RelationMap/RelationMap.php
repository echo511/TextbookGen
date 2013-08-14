<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Plugin\RelationMap;

use Echo511\TextbookGen\IIndex;
use Echo511\TextbookGen\ISnippet;
use Echo511\TextbookGen\Plugin\Metadata\Analyzator;
use Exception;
use Nette\Object;


/**
 * Map of relation between snippets.
 */
class RelationMap extends Object
{

	/** @var array Includer's human readable designation => Snippet's hash */
	private $nameToHash = array();

	/** @var array */
	private $hashToName = array();

	/** @var array */
	private $nameerencing;

	/** @var array */
	private $nameerenced;

	/** @var Analyzator */
	private $analyzator;

	/** @var IIndex */
	private $index;


	/**
	 * @param Analyzator $analyzator
	 * @param IIndex $index
	 */
	public function __construct(Analyzator $analyzator, IIndex $index)
	{
		$this->analyzator = $analyzator;
		$this->index = $index;
	}



	/**
	 * Return snippets this on is nameerenced by.
	 * @param ISnippet $nameerenced
	 * @return ISnippet[]
	 */
	public function getReferencing(ISnippet $nameerenced)
	{
		if (isset($this->nameerencing[$nameerenced->getHash()])) {
			return $this->nameerencing[$nameerenced->getHash()];
		}
		return array();
	}



	/**
	 * Return snippets this on is nameerencing to.
	 * @param ISnippet $nameerencing
	 * @return ISnippet[]
	 */
	public function getReferenced(ISnippet $nameerencing)
	{
		if (isset($this->nameerenced[$nameerencing->getHash()])) {
			return $this->nameerenced[$nameerencing->getHash()];
		}
		return array();
	}



	/**
	 * Translate snippet to nameerence variable.
	 * @param ISnippet $snippet
	 * @return string|boolean
	 */
	public function getNameOfSnippet(ISnippet $snippet)
	{
		if (isset($this->hashToName[$snippet->hash])) {
			return $this->hashToName[$snippet->hash];
		}
		return false;
	}



	/**
	 * Translate nameerence variable to snippet.
	 * @param string $name
	 * @return ISnippet|boolean
	 */
	public function getSnippetByName($name)
	{
		if (isset($this->nameToHash[$name])) {
			$hash = $this->nameToHash[$name];
			return $this->index->get($hash);
		}
		return false;
	}



	/**
	 * Create relation map.
	 * @throws Exception
	 */
	public function createRelationMap()
	{
		// Translate name to hash
		foreach ($this->index->getAll() as $snippet) {
			$name = $this->analyzator->getAttribute('name', $snippet);

			if ($name) { // nameerence specified in content
				if (isset($this->nameToHash[$name])) {
					throw new Exception("Includer's name already exists.");
				} else {
					$this->nameToHash[$name] = $snippet->getHash();
					$this->hashToName[$snippet->getHash()] = $name;
				}
			}
		}

		// Create relation map
		foreach ($this->index->getAll() as $snippet) {
			$nameerenced = $this->analyzator->getAttributes('include', $snippet);

			foreach ($nameerenced as $name) {
				if (!isset($this->nameToHash[$name])) {
					throw new Exception("Invalid includer nameerence $name.");
				}
				$nameerencing = $snippet;
				$nameerenced = $this->index->get($this->nameToHash[$name]);

				$this->addRelationship($nameerencing, $nameerenced);
			}
		}
	}



	/**
	 * Add relationship between two snippets.
	 * @param ISnippet $nameerencing
	 * @param ISnippet $nameerenced
	 */
	protected function addRelationship(ISnippet $nameerencing, ISnippet $nameerenced)
	{
		$this->nameerencing[$nameerenced->getHash()][$nameerencing->getHash()] = $nameerencing;
		$this->nameerenced[$nameerencing->getHash()][$nameerenced->getHash()] = $nameerenced;
	}



}