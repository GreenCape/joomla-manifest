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
 * Template Manifest
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class TemplateManifest extends Manifest
{
	/**
	 * @var string The client attribute allows you to specify for which application client the template is available.
	 */
	protected $client = null;

	/**
	 * Constructor
	 *
	 * @param string $xml Optional XML string to preset the manifest
	 */
	public function __construct($xml = null)
	{
		$this->type = 'template';
		/** @todo Implement PositionSection */
		$this->map['positions']      = 'VerbatimSection';

		// Legacy Joomla! 1.5 sections
		$this->map['administration'] = 'AdminSection';
		$this->map['images']         = 'FileSection';
		$this->map['css']            = 'FileSection';

		if (!is_null($xml))
		{
			$this->set($xml);
		}
	}

	/**
	 * Getter and Setter
	 */

	/**
	 * Get the name of the client application
	 *
	 * @return string Name of the which application client for which the new module is available
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * Set the name of the client application
	 *
	 * @param string $client Name of the which application client for which the new module is available
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setClient($client)
	{
		$this->client = $client;

		return $this;
	}

	/**
	 * Section interface
	 */

	/**
	 * Get the attributes for the module manifest
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		$attributes = parent::getAttributes();
		if (!empty($this->client))
		{
			$attributes['@client'] = $this->getClient();
		}

		return $attributes;
	}
}
