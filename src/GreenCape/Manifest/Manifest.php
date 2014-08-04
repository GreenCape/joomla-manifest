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

/**
 * Class Manifest
 *
 * @package GreenCape\Manifest
 */
abstract class Manifest
{
	/**
	 * Manifest Attributes
	 */

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
	 * Metadata
	 */

	/** @var string Raw component name (e.g. com_banners). This is a translatable field. */
	protected $name = '';

	/** @var string Author's name (e.g. Joomla! Project) */
	protected $author = '';

	/** @var string Date of creation or release (e.g. April 2006) */
	protected $creationDate = '';

	/** @var string The year (or range) for the copyright statement (e.g. 2005 - 2011) */
	protected $copyrightYear = '';

	/** @var string The owner of the copyright (e.g. Open Source Matters) */
	protected $copyrightOwner = '';

	/** @var string A license statement */
	protected $license = 'GNU General Public License version 2 or later; see LICENSE.txt';

	/** @var string Author's email address (e.g. admin@joomla.org) */
	protected $authorEmail = '';

	/** @var string URL to the author's website (e.g. www.joomla.org) */
	protected $authorUrl = '';

	/** @var string The version number of the extension (e.g. 1.6.0) */
	protected $version = '';

	/** @var string The description of the extension. This is a translatable field. (e.g. COM_BANNERS_XML_DESCRIPTION) */
	protected $description = '';

	/**
	 * Sections
	 */

	/** @var FileSection[] */
	protected $sections = array();

	/**
	 * Render the content as XML
	 *
	 * @return string
	 */
	final public function __toString()
	{
		$xml = new \GreenCape\Xml\Converter($this->getStructure());

		return (string) $xml;
	}

	/**
	 * @param string $tag
	 *
	 * @return array
	 */
	public function getManifestRoot($tag)
	{
		$data = array(
			'@type'    => $this->getType(),
			'@version' => $this->getTarget(),
			'@method'  => $this->getMethod(),
			$tag       => array()
		);
		$this->addAttributes($data);

		return $data;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @param string $version
	 *
	 * @return $this
	 */
	public function setVersion($version)
	{
		$this->version = $version;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Add additional attributes to the manifest.
	 *
	 * This method should be overwritten by derived classes, if the manifest type requires additional attributes.
	 *
	 * @param array &$data
	 */
	protected function addAttributes(&$data)
	{
	}

	/**
	 * @param array &$data
	 */
	protected function addMetadata(&$data)
	{
		if (empty($this->creationDate))
		{
			$this->setCreationDate();
		}

		$this->addElement($data, 'name');
		$this->addElement($data, 'author');
		$this->addElement($data, 'creationDate');
		$this->addElement($data, 'copyright');
		$this->addElement($data, 'license');
		$this->addElement($data, 'authorEmail');
		$this->addElement($data, 'authorUrl');
		$this->addElement($data, 'version');
		$this->addElement($data, 'description');
	}

	/**
	 * @param array  &$data
	 * @param string $key
	 */
	private function addElement(&$data, $key)
	{
		$value = call_user_func(array($this, 'get' . ucfirst($key)));
		if (!empty($value))
		{
			$data[] = array($key => $value);
		}
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		return $this->target;
	}

	/**
	 * @param $version
	 *
	 * @return $this
	 */
	public function setTarget($version)
	{
		$this->target = $version;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthor()
	{
		return $this->author;
	}

	/**
	 * @param string $author
	 *
	 * @return $this
	 */
	public function setAuthor($author)
	{
		$this->author = $author;
		if (empty($this->copyrightOwner))
		{
			$this->copyrightOwner = $author;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthorEmail()
	{
		return $this->authorEmail;
	}

	/**
	 * @param string $authorEmail
	 *
	 * @return $this
	 */
	public function setAuthorEmail($authorEmail)
	{
		$this->authorEmail = $authorEmail;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthorUrl()
	{
		return $this->authorUrl;
	}

	/**
	 * @param string $authorUrl
	 *
	 * @return $this
	 */
	public function setAuthorUrl($authorUrl)
	{
		$this->authorUrl = $authorUrl;

		return $this;
	}

	/**
	 * @param string $year
	 * @param string $owner
	 *
	 * @return $this
	 */
	public function setCopyright($year, $owner)
	{
		$this->copyrightYear  = $year;
		if (empty($this->creationDate))
		{
			$this->creationDate = $year;
		}

		$this->copyrightOwner = $owner;
		if (empty($this->author))
		{
			$this->author = $owner;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCopyright()
	{
		return "(C) {$this->copyrightYear} {$this->copyrightOwner}. All rights reserved.";
	}

	/**
	 * @return string
	 */
	public function getCreationDate()
	{
		return $this->creationDate;
	}

	/**
	 * @param string $creationDate Defaults to 'today'
	 *
	 * @return $this
	 */
	public function setCreationDate($creationDate = 'today')
	{
		$datetime           = strtotime($creationDate);
		$this->creationDate = date('F Y', $datetime);
		if (empty($this->copyrightYear))
		{
			$this->copyrightYear = date('Y', $datetime);
			if ($this->copyrightYear != date('Y'))
			{
				$this->copyrightYear .= ' - ' . date('Y');
			}
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 *
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLicense()
	{
		return $this->license;
	}

	/**
	 * @param string $license
	 *
	 * @return $this
	 */
	public function setLicense($license)
	{
		$this->license = $license;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		if (empty($this->description))
		{
			$this->description = strtoupper("{$name}_XML_DESCRIPTION");
		}

		return $this;
	}

	/**
	 * @param string      $tag
	 * @param FileSection $section
	 *
	 * @return $this
	 */
	public function setSection($tag, $section)
	{
		$this->sections[$tag] = $section;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getStructure()
	{
		$root = version_compare($this->target, 1.5, '>') ? 'extension' : 'install';
		$data = $this->getManifestRoot($root);

		$this->addMetadata($data[$root]);

		foreach ($this->sections as $tag => $section)
		{
			$element = array();
			$element[$tag] = $section->getStructure();
			foreach ($section->getAttributes() as $attribute => $value)
			{
				$element[$attribute] = $value;
			}
			$data[$root][] = $element;
		}

		return $data;
	}
}
