<?php
/**
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
 * Admin Section
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class AdminSection implements Section
{
    /** @var MenuSection The component menu */
    protected $menu;

    /** @var FileSection The back-end files */
    protected $files;

    /** @var LanguageSection Legacy 1.5 language support */
    protected $language;

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

            $menu = $submenu = null;

            foreach ($value as $section) {
                if (isset($section['menu'])) {
                    $menu = $section;
                    continue;
                }

                if (isset($section['submenu'])) {
                    $submenu = $section;
                    continue;
                }

                if (isset($section['files'])) {
                    $this->files = new FileSection($section);
                    continue;
                }

                if (isset($section['languages'])) {
                    $this->language = new LanguageSection($section);
                    continue;
                }
            }
            $this->menu = new MenuSection($menu, $submenu);
        }

        return $this;
    }

    /**
     * Getter and setter
     */

    /**
     * Get the menu
     *
     * @return MenuSection The menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set the menu
     *
     * @param MenuSection $menu The menu
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setMenu(MenuSection $menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get the back-end files
     *
     * @return FileSection The back-end files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set the back-end files
     *
     * @param FileSection $files The back-end files
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setFiles(FileSection $files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Get the language files
     *
     * @return LanguageSection
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set the language files
     *
     * @param LanguageSection $language
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setLanguage(LanguageSection $language)
    {
        $this->language = $language;

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

        if (!empty($this->menu)) {
            $structure = $this->menu->getStructure();
        }

        if (!empty($this->files)) {
            $element          = [];
            $element['files'] = $this->files->getStructure();

            foreach ($this->files->getAttributes() as $attribute => $value) {
                $element[$attribute] = $value;
            }

            $structure[] = $element;
        }

        if (!empty($this->language)) {
            $element              = [];
            $element['languages'] = $this->language->getStructure();

            foreach ($this->language->getAttributes() as $attribute => $value) {
                $element[$attribute] = $value;
            }

            $structure[] = $element;
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
