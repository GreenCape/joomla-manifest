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

use BadMethodCallException;
use GreenCape\Xml\Converter;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Class Manifest
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
abstract class Manifest implements Section
{
	/**
	 * Manifest Attributes
	 */

	/**
	 * @var string This attribute describes the type of the extension for the installer.
	 *             Based on this type further requirements to sub-tags apply.
	 */
	protected $type;

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

	/** @var Section[] */
	protected $sections = array();

	/** @var array Section map */
	protected $map = array(
		'install'       => 'SqlSection',
		'uninstall'     => 'SqlSection',
		'update'        => 'SchemaSection',
		'files'         => 'FileSection',
		'languages'     => 'LanguageSection',
		'media'         => 'MediaSection',
		'updateservers' => 'ServerSection',
		'params'        => 'VerbatimSection',
		'config'        => 'VerbatimSection',
	);

	/**
	 * Install hooks
	 */

	/** @var string Install file, deprecated in 1.6 */
	protected $installFile;

	/** @var string Uninstall file, deprecated in 1.6 */
	protected $uninstallFile;

	/** @var string Install/update/uninstall file, new in 1.6 */
	protected $scriptFile;

	/**
	 * Magic methods
	 */

	/**
	 * Render the content as XML
	 *
	 * @return string The XML string representation of the manifest
	 */
	public function __toString()
	{
		$xml = new Converter($this->getStructure());

		return (string) $xml;
	}

	/**
	 * Public API
	 */

	/**
	 * Load a manifest file
	 *
	 * @param string $file The manifest filename
	 *
	 * @return Manifest
	 */
	public static function load($file)
	{
		$xml = new Converter($file);
		$type = empty($xml['@type']) ? 'language' : $xml['@type'];
		$classname = '\\GreenCape\\Manifest\\' . ucfirst($type) . 'Manifest';

		return new $classname($xml);
	}

	/**
	 * Set the manifest values and sections from XML
	 *
	 * @param Converter $xml
	 *
	 * @return $this This object, to provide a fluent interface
	 * @throws UnexpectedValueException on unsupported attributes
	 */
	protected function set(Converter $xml)
	{
		// Remove type and comments, if any
		unset($xml['#comment'], $xml['@type']);

		// Get attributes
		foreach ($xml as $key => $value)
		{
			if ($key[0] !== '@')
			{
				continue;
			}

			$attribute = trim(substr($key, 1));
			if (empty($attribute))
			{
				continue;
			}
			if ($attribute === 'version')
			{
				$attribute = 'target';
			}
			$method = 'set' . ucfirst($attribute);
			if (!is_callable(array($this, $method)))
			{
				throw new UnexpectedValueException("Can't handle attribute '$attribute'");
			}
			$this->$method($value);
		}

		// Get children
		foreach ($xml as $tag => $root)
		{
			if (strpos($tag, '@') === 0)
			{
				continue;
			}
			foreach ((array) $root as $section)
			{
				$key = $value = null;
				foreach ((array) $section as $key => $value)
				{
					if ($key[0] !== '@' && $key[0] !== '#')
					{
						break;
					}
				}
				if ($key === 'copyright')
				{
					if (preg_match('~^\D*(\d{4})[ \d-]+(.*?)(?:\.?\s+All rights reserved\.)?$~', $value, $match))
					{
						$this->setCopyright($match[1], preg_replace('~\s+~', ' ', trim($match[2])));
					}
				}
				else
				{
					if (isset($this->map[$key]))
					{
						$classname = '\\GreenCape\\Manifest\\' . $this->map[$key];
						$this->removeSection($key);
						$this->addSection($key, new $classname($section));
						break;
					}
					$method = 'set' . ucfirst($key);
					if (!is_callable(array($this, $method)))
					{
						throw new UnexpectedValueException("Can't handle section '$key'");
					}
					$this->$method($value);
				}
			}
		}

		return $this;
	}

	/**
	 * Add a section to the manifest
	 *
	 * @param string  $tag     The tag of the section
	 * @param Section $section The content of the section
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function addSection($tag, Section $section)
	{
		$this->sections[$tag] = $section;

		return $this;
	}

	/**
	 * Get a section from the manifest
	 *
	 * @param string $tag The tag of the section
	 *
	 * @return Section The content of the section
	 */
	public function getSection($tag)
	{
		return $this->sections[$tag];
	}

	/**
	 * Remove a section from the manifest
	 *
	 * @param string $tag The tag of the section
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function removeSection($tag)
	{
		unset($this->sections[$tag]);

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
		$root = version_compare($this->target, 1.5, '>') ? 'extension' : 'install';
		$data = $this->getManifestRoot($root);

		$this->addMetadata($data[$root]);

		foreach ($this->sections as $tag => $section)
		{
			$element       = $section->getAttributes();
			$element[$tag] = $section->getStructure();
			$data[$root][] = $element;
		}

		$this->addInstallHooks($data[$root]);

		return $data;
	}

	/**
	 * Get the attributes for the manifest
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return array(
			'@type'    => $this->getType(),
			'@version' => $this->getTarget(),
			'@method'  => $this->getMethod()
		);
	}

	/**
	 * Get the manifest root element
	 *
	 * @param string $tag The root tag (version dependent)
	 *
	 * @return array
	 */
	protected function getManifestRoot($tag)
	{
		$data       = $this->getAttributes();
		$data[$tag] = array();

		return $data;
	}

	/**
	 * Add the meta data to the structure
	 *
	 * @param array &$data The current structure
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
	 * Add the install hooks to the structure
	 *
	 * @param array &$data The current structure
	 */
	protected function addInstallHooks(&$data)
	{
		$this->addElement($data, 'installfile');
		$this->addElement($data, 'uninstallfile');
		$this->addElement($data, 'scriptfile');
	}

	/**
	 * Add a single element to the structure
	 *
	 * Only non-empty elements will be added.
	 *
	 * @param array  &$data The current structure
	 * @param string $key   The meta data field
	 */
	protected function addElement(&$data, $key)
	{
		$value = preg_replace('~\s+~', ' ', $this->{'get' . ucfirst($key)}());
		if (!empty($value))
		{
			$data[] = array($key => $value);
		}
	}

	/**
	 * Getter and setter
	 */

	/**
	 * Get the manifest type
	 *
	 * @return string This attribute describes the type of the extension for the installer.
	 *                Based on this type further requirements to sub-tags apply.
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Manifest type cannot be set
	 * Method is included for symmetry reasons.
	 *
	 * @throws BadMethodCallException
	 */
	public function setType()
	{
		throw new BadMethodCallException('Manifest type cannot be set.');
	}

	/**
	 * Get the target version
	 *
	 * @return string String that identifies the version of Joomla for which this extension is developed.
	 */
	public function getTarget()
	{
		return $this->target;
	}

	/**
	 * Set the target version
	 *
	 * @param string $version String that identifies the version of Joomla for which this extension is developed.
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setTarget($version)
	{
		$this->target = $version;

		return $this;
	}

	/**
	 * Get the installation method
	 *
	 * @return string Installation method, one of 'install', 'upgrade'
	 *                The 'install' value means the installer will gracefully stop
	 *                if it finds any existing file/folder of the new extension.
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Set the installation method
	 *
	 * @param string $method Installation method, one of 'install', 'upgrade'
	 *                       The 'install' value means the installer will gracefully stop
	 *                       if it finds any existing file/folder of the new extension.
	 *
	 * @return $this This object, to provide a fluent interface
	 * @throws InvalidArgumentException
	 */
	public function setMethod($method)
	{
		$allowed = array('install', 'upgrade');
		if (!in_array($method, $allowed, true))
		{
			throw new InvalidArgumentException('Method must be one of ' . implode('|', $allowed) . '.');
		}
		$this->method = $method;

		return $this;
	}

	/**
	 * Get the extension name
	 *
	 * @return string Raw component name (e.g. com_banners). This is a translatable field.
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the extension name
	 *
	 * The description is preset to "<name>_XML_DESCRIPTION", if not already set.
	 *
	 * @param string $name Raw component name (e.g. com_banners). This is a translatable field.
	 *
	 * @return $this This object, to provide a fluent interface
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
	 * Get the author's name
	 *
	 * @return string Author's name (e.g. Joomla! Project)
	 */
	public function getAuthor()
	{
		return $this->author;
	}

	/**
	 * Set the author's name
	 *
	 * The copyright owner is preset to "<name>", if not already set.
	 *
	 * @param string $author Author's name (e.g. Joomla! Project)
	 *
	 * @return $this This object, to provide a fluent interface
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
	 * Get the creation date
	 *
	 * @return string Date of creation or release (e.g. April 2006)
	 */
	public function getCreationDate()
	{
		return $this->creationDate;
	}

	/**
	 * Set the creation date
	 *
	 * The copyright year is preset to the year of the date, if not already set.
	 *
	 * @param string $creationDate Date of creation or release (e.g. April 2006). Defaults to 'today'.
	 *                             Any input recognized by strtotime() can be used.
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setCreationDate($creationDate = 'today')
	{
		$datetime           = strtotime($creationDate);
		$this->creationDate = date('F Y', $datetime);
		if (empty($this->copyrightYear))
		{
			$this->setCopyright(date('Y', $datetime), $this->copyrightOwner);
		}

		return $this;
	}

	/**
	 * Get the copyright statement
	 *
	 * '(C)' is prepended and 'All rights reserved.' is appended automatically.
	 *
	 * @return string The copyright string
	 */
	public function getCopyright()
	{
		return "(C) {$this->copyrightYear} {$this->copyrightOwner}. All rights reserved.";
	}

	/**
	 * Set the copyright information
	 *
	 * If the copyright year is different from the current year, and $createRange
	 * is set to true, the copyright year is expanded to a range
	 * <copyright year> - <current year>.
	 *
	 * The creation date is preset to the copyright year, if not already set.
	 * The author is preset to the copyright owner, if not already set.
	 *
	 * @param string $year        The year for the copyright statement (e.g. 2011)
	 * @param string $owner       The owner of the copyright (e.g. Open Source Matters)
	 * @param bool   $createRange Whether or not to create a range of years (default: true)
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setCopyright($year, $owner, $createRange = true)
	{
		$this->copyrightYear  = $year;
		if (empty($this->creationDate))
		{
			$this->creationDate = $year;
		}
		if ($createRange && $this->copyrightYear !== date('Y'))
		{
			$this->copyrightYear .= ' - ' . date('Y');
		}

		$this->copyrightOwner = $owner;
		if (empty($this->author))
		{
			$this->author = $owner;
		}

		return $this;
	}

	/**
	 * Get the license statement
	 *
	 * @return string A license statement
	 */
	public function getLicense()
	{
		return $this->license;
	}

	/**
	 * Set the license statement
	 *
	 * The license statement is preset with 'GNU General Public License version 2 or later; see LICENSE.txt'.
	 *
	 * @param string $license A license statement
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setLicense($license)
	{
		$this->license = $license;

		return $this;
	}

	/**
	 * Get the author's email address
	 *
	 * @return string Author's email address (e.g. admin@joomla.org)
	 */
	public function getAuthorEmail()
	{
		return $this->authorEmail;
	}

	/**
	 * Set the author's email address
	 *
	 * @param string $authorEmail Author's email address (e.g. admin@joomla.org)
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setAuthorEmail($authorEmail)
	{
		$this->authorEmail = $authorEmail;

		return $this;
	}

	/**
	 * Get the author's URL
	 *
	 * @return string URL to the author's website (e.g. www.joomla.org)
	 */
	public function getAuthorUrl()
	{
		return $this->authorUrl;
	}

	/**
	 * Set the author's URL
	 *
	 * @param string $authorUrl URL to the author's website (e.g. www.joomla.org)
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setAuthorUrl($authorUrl)
	{
		$this->authorUrl = $authorUrl;

		return $this;
	}

	/**
	 * Get the extension's version number
	 *
	 * @return string The version number of the extension (e.g. 1.6.0)
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * Set the extension's version number
	 *
	 * @param string $version The version number of the extension (e.g. 1.6.0)
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setVersion($version)
	{
		$this->version = $version;

		return $this;
	}

	/**
	 * Get the description
	 *
	 * @return string The description of the extension. This is a translatable field. (e.g. COM_BANNERS_XML_DESCRIPTION)
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set the description
	 *
	 * @param string $description The description of the extension. This is a translatable field. (e.g. COM_BANNERS_XML_DESCRIPTION)
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Get the install file
	 *
	 * Deprecated in Joomla! 1.6
	 *
	 * @return string The name of the file
	 */
	public function getInstallFile()
	{
		return $this->installFile;
	}

	/**
	 * Set the install file
	 *
	 * Deprecated in Joomla! 1.6
	 *
	 * @param string $installFile The name of the file
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setInstallFile($installFile)
	{
		$this->installFile = $installFile;

		return $this;
	}

	/**
	 * Get the uninstall file
	 *
	 * Deprecated in Joomla! 1.6
	 *
	 * @return string The name of the file
	 */
	public function getUninstallFile()
	{
		return $this->uninstallFile;
	}

	/**
	 * Set the uninstall file
	 *
	 * Deprecated in Joomla! 1.6
	 *
	 * @param string $uninstallFile The name of the file
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setUninstallFile($uninstallFile)
	{
		$this->uninstallFile = $uninstallFile;

		return $this;
	}

	/**
	 * Get the install/update/uninstall script
	 *
	 * New in Joomla! 1.6
	 *
	 * @return string The name of the file
	 */
	public function getScriptFile()
	{
		return $this->scriptFile;
	}

	/**
	 * Set the install/update/uninstall script
	 *
	 * New in Joomla! 1.6
	 *
	 * @param string $scriptFile The name of the file
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function setScriptFile($scriptFile)
	{
		$this->scriptFile = $scriptFile;

		return $this;
	}
}
