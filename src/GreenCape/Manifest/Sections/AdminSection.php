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
 * Admin Section
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class AdminSection implements Section
{
	/** @var MenuSection The component menu */
	protected $menu = null;

	/** @var FileSection The back-end files */
	protected $files = null;

	/** @var LanguageSection Legacy 1.5 language support */
	protected $language = null;

	/**
	 * Constructor
	 *
	 * @param array $data Optional XML structure to preset the manifest
	 */
	public function __construct($data = null)
	{
		if (!is_null($data))
		{
			$this->set($data);
		}
	}

	/**
	 * Set the section values from XML structure
	 *
	 * @param array $data
	 *
	 * @return $this This object, to provide a fluent interface
	 * @throws \UnexpectedValueException on unsupported attributes
	 */
	protected function set($data)
	{
		foreach ($data as $key => $value)
		{
			if ($key[0] == '@')
			{
				$attribute = substr($key, 1);
				$method = 'set' . ucfirst($attribute);
				if (!is_callable(array($this, $method)))
				{
					throw new \UnexpectedValueException("Can't handle attribute '$attribute'");
				}
				$this->$method($value);

				continue;
			}
			$menu = $submenu = null;
			foreach ($value as $section)
			{
				if (isset($section['menu']))
				{
					$menu = $section;
					continue;
				}
				if (isset($section['submenu']))
				{
					$submenu = $section;
					continue;
				}
				if (isset($section['files']))
				{
					$this->files = new FileSection($section);
					continue;
				}
				if (isset($section['languages']))
				{
					$this->language = new LanguageSection($section);
					continue;
				}
			}
			$this->menu = new MenuSection($menu, $submenu);
		}

		return $this;
	}

	/**
	 * Getter and setter
	 */

	/**
	 * Get the menu
	 *
	 * @return MenuSection The menu
	 */
	public function getMenu()
	{
		return $this->menu;
	}

	/**
	 * Set the menu
	 *
	 * @param MenuSection $menu The menu
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setMenu(MenuSection $menu)
	{
		$this->menu = $menu;

		return $this;
	}

	/**
	 * Get the back-end files
	 *
	 * @return FileSection The back-end files
	 */
	public function getFiles()
	{
		return $this->files;
	}

	/**
	 * Set the back-end files
	 *
	 * @param FileSection $files The back-end files
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setFiles(FileSection $files)
	{
		$this->files = $files;

		return $this;
	}

	/**
	 * Get the language files
	 *
	 * @return LanguageSection
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * Set the language files
	 *
	 * @param LanguageSection $language
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setLanguage(LanguageSection $language)
	{
		$this->language = $language;

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

		if (!empty($this->menu))
		{
			$structure = $this->menu->getStructure();
		}

		if (!empty($this->files))
		{
			$element = array();
			$element['files'] = $this->files->getStructure();
			foreach ($this->files->getAttributes() as $attribute => $value)
			{
				$element[$attribute] = $value;
			}
			$structure[] = $element;
		}

		if (!empty($this->language))
		{
			$element = array();
			$element['languages'] = $this->language->getStructure();
			foreach ($this->language->getAttributes() as $attribute => $value)
			{
				$element[$attribute] = $value;
			}
			$structure[] = $element;
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
