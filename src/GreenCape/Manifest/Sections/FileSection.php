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
 * File Section
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class FileSection implements Section
{
    /** @var string The base folder in the zip package */
    protected $base;

    /** @var array The file list */
    protected $files = [];

    /** @var array The folder list */
    protected $folders = [];

    /** @var string The tag for the file entries */
    protected $fileTag = 'filename';

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

                if ($attribute === 'folder') {
                    $attribute = 'base';
                }

                $method = 'set' . ucfirst($attribute);

                if (!is_callable([$this, $method])) {
                    throw new UnexpectedValueException("Can't handle attribute '$attribute'");
                }

                $this->$method($value);

                continue;
            }

            if (isset($value['file'])) {
                $this->files[] = $this->reTag($value, 'file', 'filename');
            } elseif (isset($value['filename'])) {
                $this->files[] = $value;
            } elseif (isset($value['folder'])) {
                $this->folders[] = $value;
            } else {
                $this->files = $value;
            }
        }

        return $this;
    }

    /**
     * Exchange the element tags, if necessary
     *
     * @param array  $entry  The file or folder entry
     * @param string $oldTag The old tag
     * @param string $newTag The new tag
     *
     * @return array
     */
    private function reTag($entry, $oldTag, $newTag)
    {
        if ($newTag === $oldTag) {
            return $entry;
        }

        $modifiedEntry = [];

        foreach ($entry as $tag => $value) {
            if ($tag === $oldTag) {
                $modifiedEntry[$newTag] = $value;
            } else {
                $modifiedEntry[$tag] = $value;
            }
        }

        return $modifiedEntry;
    }

    /**
     * Add a file to the section
     *
     * @param string $filename   The name of the file
     * @param array  $attributes Optional attributes for this entry
     *
     * @return $this This object, to provide a fluent interface
     */
    public function addFile($filename, $attributes = [])
    {
        $element = [$this->fileTag => $filename];

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
            if ($element['filename'] === $filename) {
                unset($this->files[$key]);
            }
        }

        return $this;
    }

    /**
     * Add a folder to the section
     *
     * @param string $folder     The name of the folder
     * @param array  $attributes Optional attributes for this entry
     *
     * @return $this This object, to provide a fluent interface
     */
    public function addFolder($folder, $attributes = [])
    {
        $element = ['folder' => $folder];

        foreach ($attributes as $key => $value) {
            $element["@{$key}"] = (string)$value;
        }

        $this->folders[] = $element;

        return $this;
    }

    /**
     * Getter and setter
     */

    /**
     * Remove a folder from the section
     *
     * @param string $folder The name of the folder
     *
     * @return $this This object, to provide a fluent interface
     */
    public function removeFolder($folder)
    {
        foreach ($this->folders as $key => $element) {
            if ($element['folder'] === $folder) {
                unset($this->folders[$key]);
            }
        }

        return $this;
    }

    /**
     * Get the tag for the file entries
     *
     * @return string The tag for the file entries
     */
    public function getFileTag()
    {
        return $this->fileTag;
    }

    /**
     * Set the tag for the file entries
     *
     * @param string $fileTag The tag for the file entries
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setFileTag($fileTag)
    {
        $this->fileTag = $fileTag;

        return $this;
    }

    /**
     * Get the base folder within the distribution package (zip file)
     *
     * @return string The base folder within the distribution package
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Section interface
     */

    /**
     * Set the base folder within the distribution package (zip file)
     *
     * @param string $base The base folder within the distribution package
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setBase($base)
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Get the section structure
     *
     * @param string $fileTag   Alternative tag for files
     * @param string $folderTag Alternative tag for folders
     *
     * @return array
     */
    public function getStructure($fileTag = 'filename', $folderTag = 'folder')
    {
        $structure = [];

        foreach ($this->files as $file) {
            $structure[] = $this->reTag($file, 'filename', $fileTag);
        }

        foreach ($this->folders as $folder) {
            $structure[] = $this->reTag($folder, 'folder', $folderTag);
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
        $attributes = [];

        if (!empty($this->base)) {
            $attributes['@folder'] = $this->base;
        }

        return $attributes;
    }
}
