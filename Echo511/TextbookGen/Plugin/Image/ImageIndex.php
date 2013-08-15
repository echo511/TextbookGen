<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Plugin\Image;

use Exception;
use Nette\Object;


/**
 * Index of all images available.
 */
class ImageIndex extends Object
{

	/** @var string Path to prepend before image url */
	public $basePath;

	/** @var array */
	private $images = array();


	/**
	 * Add image.
	 * @param string $filename
	 * @throws Exception
	 */
	public function add($filename)
	{
		if (file_exists($filename)) {
			$this->images[basename($filename)] = $filename;
		} else {
			throw new Exception("Image not found at $filename.");
		}
	}



	/**
	 * Iterate all images.
	 * @return array
	 */
	public function getAll()
	{
		return $this->images;
	}



	/**
	 * Translate name of image to full filename.
	 * @param string $name
	 * @return string
	 */
	public function getFilenameByName($name)
	{
		return $this->images[$name];
	}



	/**
	 * Translate name of file to url where it will be abailable.
	 * @param string $name
	 * @return string
	 * @throws Exception
	 */
	public function getUrlByName($name)
	{
		if (isset($this->images[$name])) {
			if (isset($this->basePath)) {
				return $this->basePath . '/' . $name;
			} else {
				return $name;
			}
		}
		throw new Exception("Image with name $name does not exist.");
	}



}