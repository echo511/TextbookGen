<?php

/**
 * This file is part of TextbookGen.
 *
 * Copyright (c) 2013 Nikolas Tsiongas (http://congi.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Echo511\TextbookGen\Plugin\Metadata;


/**
 * Remove or replace attributes from content.
 */
class Manipulator extends \Nette\Object
{

	/**
	 * Remove attribute from content.
	 * @param string $name
	 * @param string $content
	 * @return string
	 */
	public function removeAttribute($name, $content)
	{
		$pattern = '/@' . $name . ' ([^"\n\r]*[^\s])/';
		$content = preg_replace($pattern, "", $content);

		$pattern = '/@' . $name . ':"([^@]*)"/';
		$content = preg_replace($pattern, "", $content);

		$pattern = '/@' . $name . ':([^"\s][^"\s]*)/';
		$content = preg_replace($pattern, "", $content);

		return $content;
	}



	/**
	 * Remove attributes from content.
	 * @param string $name Attribute's name
	 * @param string $content
	 * @return string
	 */
	public function removeAttributes($name, $content)
	{
		return $this->removeAttribute($name, $content);
	}



	/**
	 * Remove all attributes from content.
	 * @param string $name Attribute's name
	 * @param string $content
	 * @return string
	 */
	public function removeAll($content)
	{
		return $this->removeAttribute('.*', $content);
	}



	/**
	 * Replace attribute in content with content.
	 * @param string $name Attribute's name
	 * @param string $value Attribute's value
	 * @param string $for Replacement
	 * @param string $content
	 * @return string
	 */
	public function replaceAttributes($name, $value, $for, $content)
	{
		$content = str_replace('@' . $name . ' ' . $value, $for, $content);
		$content = str_replace('@' . $name . ':' . $value, $for, $content);
		$content = str_replace('@' . $name . ':"' . $value . '"', $for, $content);
		return $content;
	}



}