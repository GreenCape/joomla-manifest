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
 * File Section
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class FileSection implements Section
{
	/** @var string The base folder in the zip package */
	protected $base = null;

	/** @var array The file list */
	protected $files = array();

	/** @var array The folder list */
	protected $folders = array();

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
				if ($attribute == 'folder')
				{
					$attribute = 'base';
				}
				$method = 'set' . ucfirst($attribute);
				if (!is_callable(array($this, $method)))
				{
					throw new \UnexpectedValueException("Can't handle attribute '$attribute'");
				}
				$this->$method($value);

				continue;
			}
			if (isset($value['filename']))
			{
				$this->files[] = $value;
			}
			elseif (isset($value['folder']))
			{
				$this->folders[] = $value;
			}
			else
			{
				$this->files = $value;
			}
		}

		return $this;
	}

	/**
	 * Add a file to the section
	 *
	 * @param string $filename   The name of the file
	 * @param array  $attributes Optional attributes for this entry
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function addFile($filename, $attributes = array())
	{
		$element = array('filename' => $filename);
		foreach ($attributes as $key => $value)
		{
			$element["@{$key}"] = (string) $value;
		}
		$this->files[] = $element;

		return $this;
	}

	/**
	 * Remove a file from the section
	 *
	 * @param string $filename   The name of the file
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function removeFile($filename)
	{
		foreach ($this->files as $key => $element)
		{
			if ($element['filename'] == $filename)
			{
				unset($this->files[$key]);
			}
		}

		return $this;
	}

	/**
	 * Add a folder to the section
	 *
	 * @param string $folder     The name of the folder
	 * @param array  $attributes Optional attributes for this entry
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function addFolder($folder, $attributes = array())
	{
		$element       = array('folder' => $folder);
		foreach ($attributes as $key => $value)
		{
			$element["@{$key}"] = (string) $value;
		}
		$this->folders[] = $element;

		return $this;
	}

	/**
	 * Remove a folder from the section
	 *
	 * @param string $folder The name of the folder
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function removeFolder($folder)
	{
		foreach ($this->folders as $key => $element)
		{
			if ($element['folder'] == $folder)
			{
				unset($this->folders[$key]);
			}
		}

		return $this;
	}

	/**
	 * Getter and setter
	 */

	/**
	 * Get the base folder within the distribution package (zip file)
	 *
	 * @return string The base folder within the distribution package
	 */
	public function getBase()
	{
		return $this->base;
	}

	/**
	 * Set the base folder within the distribution package (zip file)
	 *
	 * @param string $base The base folder within the distribution package
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setBase($base)
	{
		$this->base = $base;

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

		foreach ($this->files as $file)
		{
			$structure[] = $file;
		}

		foreach ($this->folders as $folder)
		{
			$structure[] = $folder;
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
		$attributes = array();

		if (!empty($this->base))
		{
			$attributes['@folder'] = $this->base;
		}

		return $attributes;
	}
}
