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
 * @package         GreenCape\Manifest
 * @author          Niels Braczek <nbraczek@bsds.de>
 * @copyright   (C) 2014-2015 GreenCape, Niels Braczek <nbraczek@bsds.de>
 * @license         http://opensource.org/licenses/MIT The MIT license (MIT)
 * @link            http://greencape.github.io
 * @since           File available since Release 0.1.0
 */

namespace GreenCape\Manifest;

use UnexpectedValueException;

/**
 * Table Section
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class TableSection implements Section
{
    /** @var array The table list */
    protected $tables = [];

    /**
     * Constructor
     *
     * @param array $data Optional XML structure to preset the manifest
     */
    public function __construct($data = null)
    {
        if ($data !== null) {
            $this->set($data);
        }
    }

    /**
     * Set the section values from XML structure
     *
     * @param array $data
     *
     * @return $this This object, to provide a fluent interface
     * @throws UnexpectedValueException on unsupported attributes
     */
    protected function set($data)
    {
        foreach ($data as $key => $value) {
            if (strpos($key, '@') === 0) {
                $attribute = substr($key, 1);
                $method    = 'set' . ucfirst($attribute);

                if (!is_callable([$this, $method])) {
                    throw new UnexpectedValueException("Can't handle attribute '$attribute'");
                }

                $this->$method($value);

                continue;
            }

            $this->tables = $value;
        }

        return $this;
    }

    /**
     * Add a table to the section
     *
     * These are used for backups to determine which tables to backup;
     * ones marked optional are only backed up if they exist
     *
     * @param string $name     The table name with '#__' prefix
     * @param bool   $optional Whether or not the table is optional
     *
     * @return $this This object, to provide a fluent interface
     */
    public function addTable($name, $optional = false)
    {
        $element = ['table' => $name];

        if ($optional) {
            $element['@type'] = 'optional';
        }

        $this->tables[] = $element;

        return $this;
    }

    /**
     * Remove a table from the section
     *
     * @param string $name The name of the table
     *
     * @return $this This object, to provide a fluent interface
     */
    public function removeTable($name)
    {
        foreach ($this->tables as $key => $element) {
            if ($element['table'] === $name) {
                unset($this->tables[$key]);
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
        $structure = [];

        foreach ($this->tables as $table) {
            $structure[] = $table;
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
        return [];
    }
}
