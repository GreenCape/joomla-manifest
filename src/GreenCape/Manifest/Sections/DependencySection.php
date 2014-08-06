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
 * Dependency Section
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class DependencySection implements Section
{
	/** @var array The dependency list */
	protected $dependencies = array();

	/**
	 * Add a dependency to the section
	 *
	 * These are used for backups to determine which dependencies to backup;
	 * ones marked optional are only backed up if they exist
	 *
	 * @param string $type     Type of required item
	 * @param string $name     Name of required item
	 * @param string $operator Comparision operator
	 * @param string $version  Required version
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function addDependency($type, $name, $operator, $version)
	{
		$element = array('dependency' => '');
		$element['@type']     = $type;
		$element['@name']     = $name;
		$element['@operator'] = $operator;
		$element['@version']  = $version;

		$this->dependencies[] = $element;

		return $this;
	}

	/**
	 * Remove a dependency from the section
	 *
	 * @param string $name The name of the dependency
	 *
	 * @return $this This object, to provide a fluent interface
	 */
	public function removeDependency($name)
	{
		foreach ($this->dependencies as $key => $element)
		{
			if ($element['@name'] == $name)
			{
				unset($this->dependencies[$key]);
			}
		}

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

		foreach ($this->dependencies as $dependency)
		{
			$structure[] = $dependency;
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