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

class FileSection implements Section
{
	/**
	 * @var string The base folder in the zip package
	 */
	protected $base = null;

	protected $files = array();
	protected $folders = array();

	/**
	 * @param string $filename
	 * @param array  $attributes
	 *
	 * @return $this
	 */
	public function addFile($filename, $attributes = array())
	{
		$element       = array('filename' => $filename);
		foreach ($attributes as $key => $value)
		{
			$element["@{$key}"] = (string) $value;
		}
		$this->files[] = $element;

		return $this;
	}

	/**
	 * @param string $folder
	 * @param array  $attributes
	 *
	 * @return $this
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

	protected function addAttributes(&$data){}

	/**
	 * @param string $base
	 *
	 * @return $this
	 */
	public function setBase($base)
	{
		$this->base = $base;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getBase()
	{
		return $this->base;
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
