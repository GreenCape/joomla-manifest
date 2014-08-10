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
 * Library Manifest
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class LibraryManifest extends Manifest
{
	/** @var  string Name of the library */
	private $libraryname;

	/** @var  string Name of the packager */
	private $packager;

	/** @var  string URL of the packager */
	private $packagerurl;

	/**
	 * Constructor
	 *
	 * @param string $xml Optional XML string to preset the manifest
	 */
	public function __construct($xml = null)
	{
		$this->type = 'library';

		if (!is_null($xml))
		{
			$this->set($xml);
		}
	}

	/**
	 * Getter and Setter
	 */

	/**
	 * Get the name of the library
	 *
	 * @return string Name of the library
	 */
	public function getLibraryName()
	{
		return $this->libraryname;
	}

	/**
	 * Set the name of the library
	 *
	 * @param string $libraryName Name of the library
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setLibraryName($libraryName)
	{
		$this->libraryname = $libraryName;

		return $this;
	}

	/**
	 * Get the name of the packager
	 *
	 * @return string Name of the packager
	 */
	public function getPackager()
	{
		return $this->packager;
	}

	/**
	 * Set the name of the packager
	 *
	 * @param string $packagerName Name of the packager
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setPackager($packagerName)
	{
		$this->packager = $packagerName;

		return $this;
	}

	/**
	 * Get the URL of the packager
	 *
	 * @return string URL of the packager
	 */
	public function getPackagerUrl()
	{
		return $this->packagerurl;
	}

	/**
	 * Set the URL of the packager
	 *
	 * @param string $packagerUrl URL of the packager
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setPackagerUrl($packagerUrl)
	{
		$this->packagerurl = $packagerUrl;

		return $this;
	}

	/**
	 * Section interface
	 */

	/**
	 * Get the manifest structure
	 *
	 * @return array
	 */
	public function getStructure()
	{
		$data = parent::getStructure();

		$this->addElement($data['extension'], 'libraryname');
		$this->addElement($data['extension'], 'packager');
		$this->addElement($data['extension'], 'packagerurl');

		return $data;
	}
}
