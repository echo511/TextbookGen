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
	private $referencing;

	/** @var array */
	private $referenced;

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
	 * Return snippets this on is referenced by.
	 * @param ISnippet $referenced
	 * @return ISnippet[]
	 */
	public function getReferencing(ISnippet $referenced)
	{
		if (isset($this->referencing[$referenced->getHash()])) {
			return $this->referencing[$referenced->getHash()];
		}
		return array();
	}



	/**
	 * Return snippets this on is referencing to.
	 * @param ISnippet $referencing
	 * @return ISnippet[]
	 */
	public function getReferenced(ISnippet $referencing)
	{
		if (isset($this->referenced[$referencing->getHash()])) {
			return $this->referenced[$referencing->getHash()];
		}
		return array();
	}



	/**
	 * Translate snippet to name variable.
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
	 * Translate name variable to snippet.
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

			if ($name) { // name specified in content
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
			$referenced = $this->analyzator->getAttributes('include', $snippet);

			foreach ($referenced as $name) {
				if (!isset($this->nameToHash[$name])) {
					throw new Exception("Invalid includer name $name.");
				}
				$referencing = $snippet;
				$referenced = $this->index->get($this->nameToHash[$name]);

				$this->addRelationship($referencing, $referenced);
			}
		}
	}



	/**
	 * Add relationship between two snippets.
	 * @param ISnippet $referencing
	 * @param ISnippet $referenced
	 */
	protected function addRelationship(ISnippet $referencing, ISnippet $referenced)
	{
		$this->referencing[$referenced->getHash()][$referencing->getHash()] = $referencing;
		$this->referenced[$referencing->getHash()][$referenced->getHash()] = $referenced;
	}



}