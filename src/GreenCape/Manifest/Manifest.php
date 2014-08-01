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
 * @subpackage  Unittests
 * @author      Niels Braczek <nbraczek@bsds.de>
 * @copyright   (C) 2014 GreenCape, Niels Braczek <nbraczek@bsds.de>
 * @license     http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2.0 (GPLv2)
 * @link        http://www.greencape.com/
 * @since       File available since Release 0.1.0
 */

namespace GreenCape\Manifest;

abstract class Manifest
{
	/**
	 * @var string This attribute describes the type of the extension for the installer.
	 *             Based on this type further requirements to sub-tags apply.
	 */
	protected $type = null;

	/**
	 * @var string String that identifies the version of Joomla for which this extension is developed.
	 */
	protected $target = '2.5';

	/**
	 * @var string Installation method, one of 'install', 'upgrade'
	 *             The 'install' value means the installer will gracefully stop
	 *             if it finds any existing file/folder of the new extension.
	 */
	protected $method = 'install';

	/**
	 * Render the content as XML
	 *
	 * @return string
	 */
	final public function __toString()
	{
		$xml = $this->getManifestRoot();
		$this->addRootAttributes($xml);
		return $xml->saveXML();
	}

	public function getManifestRoot()
	{
		$root = new \DOMDocument('1.0', 'utf-8');
		$xml  = '<extension type="%s" version="%s" method="%s"></extension>';
		$root->loadXML(sprintf($xml, $this->getType(), $this->getTarget(), $this->getMethod()));

		return $root;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setTarget($version)
	{
		$this->target = $version;
	}

	public function getTarget()
	{
		return $this->target;
	}

	public function getMethod()
	{
		return $this->method;
	}

	protected function addRootAttributes($xml){}
}
