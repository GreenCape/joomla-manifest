<?php
/**
 * GreenCape Joomla Extension Manifests
 *
 * Copyright (c) 2014, Niels Braczek <nbraczek@bsds.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of GreenCape or Niels Braczek nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package     GreenCape\Manifest
 * @author      Niels Braczek <nbraczek@bsds.de>
 * @copyright   (C) 2014 GreenCape, Niels Braczek <nbraczek@bsds.de>
 * @license     http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2.0 (GPLv2)
 * @link        http://www.greencape.com/
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
