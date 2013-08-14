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

use Echo511\TextbookGen\ISnippet;
use Echo511\TextbookGen\Plugin\Link\ILinkProvider;
use Echo511\TextbookGen\Plugin\Metadata\Analyzator;
use Exception;
use Nette\Utils\Strings;


/**
 * Store output in file.
 */
final class FileOutputStorage extends OutputStorage implements ILinkProvider
{

	/** @var string */
	private $dir;

	/** @var string */
	private $ext;

	/** @var Analyzator */
	private $analyzator;


	/**
	 * @param string $dir Directory where perform storing.
	 * @param string $ext Extension of resulting file.
	 */
	public function __construct($dir, $ext = 'html', Analyzator $analyzator)
	{
		if (!is_dir($dir)) {
			throw new Exception("Cannot proceed. $dir is not a directory.");
		}

		$this->dir = $dir;
		$this->ext = $ext;
		$this->analyzator = $analyzator;
	}



	/* ---------- OutputStorage ---------- */

	public function doStore($output, ISnippet $snippet)
	{
		$filename = $this->getFilename($snippet);
		file_put_contents("safe://$filename", $output);
	}



	/* ---------- Additions ---------- */

	public function getLinkForSnippet(ISnippet $snippet)
	{
		$link = $this->escapeName($this->analyzator->getAttribute('name', $snippet));
		if (isset($this->ext)) {
			$link .= '.' . $this->ext;
		}
		return $link;
	}



	/**
	 * Return full filename of file snippet's output is supposed to be stored in.
	 * 
	 * @TODO do better paths
	 * 
	 * @param ISnippet $snippet
	 * @return string
	 */
	private function getFilename(ISnippet $snippet)
	{
		$filename = $this->dir . DIRECTORY_SEPARATOR . $this->escapeName($this->analyzator->getAttribute('name', $snippet));
		if (isset($this->ext)) {
			$filename .= '.' . $this->ext;
		}
		return $filename;
	}



	private function escapeName($name)
	{
		return Strings::webalize($name);
	}



}