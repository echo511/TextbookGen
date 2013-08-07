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


/**
 * Store output in file.
 */
final class FileOutputStorage extends OutputStorage
{

	/** @var string */
	private $dir;

	/** @var string */
	private $ext;


	/**
	 * @param string $dir Directory where perform storing.
	 * @param string $ext Extension of resulting file.
	 */
	public function __construct($dir, $ext = null)
	{
		if (!is_dir($dir)) {
			throw new \Exception("Cannot proceed. $dir is not a directory.");
		}

		$this->dir = $dir;
		$this->ext = $ext;
	}



	/* ---------- OutputStorage ---------- */

	public function doStore($output, ISnippet $snippet)
	{
		$filename = $this->getFilename($snippet);
		file_put_contents("safe://$filename", $output);
	}



	/* ---------- Additions ---------- */

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
		$filename = $this->dir . DIRECTORY_SEPARATOR . time();
		if (isset($this->ext)) {
			$filename .= '.' . $this->ext;
		}
		return $filename;
	}



}