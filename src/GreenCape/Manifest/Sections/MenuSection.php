<?php
/**
 * GreenCape Joomla Extension Manifests
 *
 * MIT License
 *
 * Copyright (c) 2014-2015, Niels Braczek <nbraczek@bsds.de>. All rights reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and
 * to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions
 * of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package     GreenCape\Manifest
 * @author      Niels Braczek <nbraczek@bsds.de>
 * @copyright   (C) 2014-2015 GreenCape, Niels Braczek <nbraczek@bsds.de>
 * @license     http://opensource.org/licenses/MIT The MIT license (MIT)
 * @link        http://greencape.github.io
 * @since       File available since Release 0.1.0
 */

namespace GreenCape\Manifest;

/**
 * Menu Section
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class MenuSection implements Section
{
	protected $menu = array();

	protected $submenu = array();

	/**
	 * Constructor
	 *
	 * @param array $menu Optional XML structure to preset the manifest
	 */
	public function __construct($menu = null, $submenu = null)
	{
		if (!is_null($menu))
		{
			$this->set($menu, $submenu);
		}
	}

	/**
	 * Set the section values from XML structure
	 *
	 * @param array $menu
	 * @param array $submenu
	 *
	 * @return $this This object, to provide a fluent interface
	 * @throws \UnexpectedValueException on unsupported attributes
	 */
	protected function set($menu, $submenu)
	{
		$this->menu = $menu;
		$this->submenu = $submenu['submenu'];

		return $this;
	}

	/**
	 * Add a menu item
	 *
	 * @param MenuSection|string $label
	 * @param string $link
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function addMenu($label, $link = '#')
	{
		if (is_string($label))
		{
			$this->submenu[] = array(
				'menu'  => $label,
				'@link' => $link
			);
		}
		else
		{
			$this->submenu = array_merge($this->submenu, $label->getStructure());
		}

		return $this;
	}

	/**
	 * Getter and setter
	 */

	/**
	 * Get the menu label
	 *
	 * @return string The label
	 */
	public function getLabel()
	{
		return $this->menu['menu'];
	}

	/**
	 * Set the menu label
	 *
	 * @param string $label The label
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setLabel($label)
	{
		$this->menu['menu'] = $label;

		return $this;
	}

	/**
	 * Get the icon filename
	 *
	 * @return string  The icon filename
	 */
	public function getIcon()
	{
		return $this->menu['@img'];
	}

	/**
	 * Set the icon filename
	 *
	 * @param string $icon The icon filename
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setIcon($icon)
	{
		$this->menu['@img'] = $icon;

		return $this;
	}

	/**
	 * Get the link
	 *
	 * @return string  The link
	 */
	public function getLink()
	{
		return $this->menu['@link'];
	}

	/**
	 * Set the link
	 *
	 * @param string $link The link
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setLink($link)
	{
		$this->menu['@link'] = $link;

		return $this;
	}

	/**
	 * Get an attribute
	 *
	 * @param string $key The key
	 *
	 * @return string  The parameter
	 */
	public function getAttribute($key)
	{
		return $this->menu["@{$key}"];
	}

	/**
	 * Set an attribute
	 *
	 * @param string $key   The key
	 * @param string $value The value
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setAttribute($key, $value)
	{
		$this->menu["@{$key}"] = $value;

		return $this;
	}

	/**
	 * Get the alt text
	 *
	 * @return string  The alt text
	 */
	public function getAlt()
	{
		return $this->menu['@alt'];
	}

	/**
	 * Set the alt text
	 *
	 * @param string $alt The alt text
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setAlt($alt)
	{
		$this->menu['@alt'] = $alt;

		return $this;
	}

	/**
	 * Section interface
	 */

	/**
	 * Get the section structure
	 *
	 * @return array
	 */
	public function getStructure()
	{
		$structure = array();
		$structure[] = $this->menu;
		if (!empty($this->submenu))
		{
			$structure[] = array('submenu' => $this->submenu);
		}

		return $structure;
	}

	/**
	 * Get the attributes for the section
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return array();
	}
}
