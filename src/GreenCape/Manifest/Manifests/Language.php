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
 * Language Manifest
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class LanguageManifest extends Manifest
{
	/**
	 * @var string The client attribute allows you to specify for which application client the language is available.
	 */
	protected $client = 'site';

	/** @var string The ISO code for the language */
	protected $tag = null;

	/** @var  int  */
	protected $rtl;

	/** @var  string */
	protected $locale;

	/** @var  string */
	protected $codePage;

	/** @var  string */
	protected $backwardLanguage;

	/** @var  string */
	protected $fontName;

	/**
	 * Constructor
	 *
	 * @param string $xml Optional XML string to preset the manifest
	 */
	public function __construct($xml = null)
	{
		$this->type                 = 'language';
		$this->sections['metadata'] = array();

		if (!is_null($xml))
		{
			$this->set($xml);
		}
	}

	/**
	 * Getter and Setter
	 */

	/**
	 * Set the extension name
	 *
	 * The description is preset to "<name>_XML_DESCRIPTION", if not already set.
	 *
	 * @param string $name Language name. This is a translatable field.
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setName($name)
	{
		parent::setName($name);
		$this->sections['metadata']['name'] = $name;

		return $this;
	}

	/**
	 * Get the name of the client application
	 *
	 * @return string Name of the which application client for which the language is available
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * Set the name of the client application
	 *
	 * @param string $client Name of the which application client for which the language is available
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setClient($client)
	{
		$this->client = $client;

		return $this;
	}

	/**
	 * Get the ISO tag
	 *
	 * @return string The ISO code for the language
	 */
	public function getTag()
	{
		return $this->tag;
	}

	/**
	 * Set the ISO tag
	 *
	 * @param string $tag The ISO code for the language
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setTag($tag)
	{
		$this->tag = $tag;
		$this->sections['metadata']['tag'] = $tag;

		return $this;
	}

	/**
	 * Get the PDF font name
	 *
	 * @return string PDF font name
	 */
	public function getFontName()
	{
		return $this->fontName;
	}

	/**
	 * Set the PDF font name
	 *
	 * @param string $fontName PDF font name
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setFontName($fontName)
	{
		$this->fontName = $fontName;
		$this->sections['metadata']['pdfFontName'] = $fontName;

		return $this;
	}

	/**
	 * Get the locale
	 *
	 * @return string The locale string
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * Set the locale
	 *
	 * @param string $locale The locale string
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;
		$this->sections['metadata']['locale'] = $locale;

		return $this;
	}

	/**
	 * Get RTL value
	 *
	 * @return boolean true = RTL, false = LTR
	 */
	public function getRtl()
	{
		return (bool) $this->rtl;
	}

	/**
	 * Set RTL value
	 *
	 * @param boolean $rtl  true = RTL, false = LTR
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setRtl($rtl)
	{
		$this->rtl = $rtl ? 1 : 0;
		$this->sections['metadata']['rtl'] = $this->rtl;

		return $this;
	}

	/**
	 * Get the code page
	 *
	 * @return string The code page
	 */
	public function getCodePage()
	{
		return $this->codePage;
	}

	/**
	 * Set the code page
	 *
	 * @param string $page The code page
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setCodePage($page)
	{
		$this->codePage = $page;
		$this->sections['metadata']['winCodePage'] = $page;

		return $this;
	}

	/**
	 * Set the meta data
	 *
	 * This method is called during load of an XML file.
	 * This makes the section look like a simple property to the import method.
	 *
	 * @param array $data The code page
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	protected function setMetadata($data)
	{
		foreach ($data as $entry)
		{
			foreach ($entry as $key => $value)
			{
				$this->sections['metadata'][$key] = $value;
			}
		}

		return $this;
	}

	/**
	 * Get the backward language
	 *
	 * @return string The backward language
	 */
	public function getBackwardLanguage()
	{
		return $this->backwardLanguage;
	}

	/**
	 * Set the backward language
	 *
	 * @param string $backwardLanguage The backward language
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setBackwardLanguage($backwardLanguage)
	{
		$this->backwardLanguage = $backwardLanguage;
		$this->sections['metadata']['backwardLang'] = $backwardLanguage;

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
		$data = $this->getManifestRoot('metafile');

		$this->addMetadata($data['metafile']);
		$this->addElement($data['metafile'], 'tag');

		foreach ($this->sections as $tag => $section)
		{
			$element = array();
			if ($section instanceof Section)
			{
				$element[$tag] = $section->getStructure();
				foreach ($section->getAttributes() as $attribute => $value)
				{
					$element[$attribute] = $value;
				}
			}
			else
			{
				$element[$tag] = $section;
			}
			$data['metafile'][] = $element;
		}

		return $data;
	}

	/**
	 * Get the attributes for the language manifest
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		$attributes = array();
		$attributes['@version'] = $this->getTarget();
		$attributes['@client']  = $this->getClient();

		return $attributes;
	}
}
