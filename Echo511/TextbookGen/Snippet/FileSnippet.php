<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Snippet;


/**
 * Interface for factory of FileSnippet.
 */
interface IFileSnippetFactory
{

	/** @return FileSnippet */
	function create($filename);

}


/**
 * Snippet saved in file.
 */
final class FileSnippet extends Snippet
{

	/** @var string */
	private $filename;


	/**
	 * @param string $filename
	 */
	public function __construct($filename)
	{
		if (!file_exists($filename)) {
			$this->errors[] = "File $filename does not exist.";
		} else {
			parent::__construct(file_get_contents($filename));
			$this->filenameHash($filename);
			$this->addTag('filenameHash', self::filenameHash($this));
		}
	}



	/* ---------- Additions ---------- */

	/**
	 * Return filename of file where snippet is stored.
	 * @return string
	 */
	public function getFilename()
	{
		return $this->filename;
	}



	/* ---------- Helper ---------- */

	public static function filenameHash(FileSnippet $snippet)
	{
		return sha1($snippet->getFilename());
	}



}