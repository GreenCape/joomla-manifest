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
 * SQL Section
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class SqlSection implements Section
{
    protected $elementIndex;
    protected $driverIndex;
    protected $structureIndex;
    /** @var array The file list */
    protected $files = [];

    /**
     * Constructor
     *
     * @param array $data Optional XML structure to preset the manifest
     */
    public function __construct($data = null)
    {
        $this->structureIndex = 'sql';
        $this->driverIndex    = '@driver';
        $this->elementIndex   = 'file';

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

            if (isset($value[$this->structureIndex][0])) {
                $this->files = $value[$this->structureIndex];
            } else {
                $this->files[] = $value[$this->structureIndex];
            }
        }

        return $this;
    }

    /**
     * Add a file to the section
     *
     * @param string $driver     The name of a database driver
     * @param string $filename   The name of the file
     * @param array  $attributes Optional attributes for this entry
     *
     * @return $this This object, to provide a fluent interface
     */
    public function addFile($driver, $filename, $attributes = [])
    {
        $element                     = [$this->elementIndex => $filename];
        $element[$this->driverIndex] = (string)$driver;

        foreach ($attributes as $key => $value) {
            $element["@{$key}"] = (string)$value;
        }

        $this->files[] = $element;

        return $this;
    }

    /**
     * Remove a file from the section
     *
     * @param string $filename The name of the file
     *
     * @return $this This object, to provide a fluent interface
     */
    public function removeFile($filename)
    {
        foreach ($this->files as $key => $element) {
            if ($element[$this->elementIndex] === $filename) {
                unset($this->files[$key]);
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

        foreach ($this->files as $file) {
            $structure[] = $file;
        }

        return [$this->structureIndex => $structure];
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
