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

use GreenCape\Xml\Converter;

/**
 * Library Manifest
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class LibraryManifest extends Manifest
{
    /** @var  string Name of the library */
    private $libraryName;

    /** @var  string Name of the packager */
    private $packager;

    /** @var  string URL of the packager */
    private $packagerUrl;

    /**
     * Constructor
     *
     * @param Converter $xml Optional XML string to preset the manifest
     */
    public function __construct(Converter $xml = null)
    {
        $this->type = 'library';

        if ($xml !== null) {
            $this->set($xml);
        }
    }

    /**
     * Getter and Setter
     */

    /**
     * Get the name of the library
     *
     * @return string Name of the library
     */
    public function getLibraryName()
    {
        return $this->libraryName;
    }

    /**
     * Set the name of the library
     *
     * @param string $libraryName Name of the library
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setLibraryName($libraryName)
    {
        $this->libraryName = $libraryName;

        return $this;
    }

    /**
     * Get the name of the packager
     *
     * @return string Name of the packager
     */
    public function getPackager()
    {
        return $this->packager;
    }

    /**
     * Set the name of the packager
     *
     * @param string $packagerName Name of the packager
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setPackager($packagerName)
    {
        $this->packager = $packagerName;

        return $this;
    }

    /**
     * Get the URL of the packager
     *
     * @return string URL of the packager
     */
    public function getPackagerUrl()
    {
        return $this->packagerUrl;
    }

    /**
     * Set the URL of the packager
     *
     * @param string $packagerUrl URL of the packager
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setPackagerUrl($packagerUrl)
    {
        $this->packagerUrl = $packagerUrl;

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
        $data = parent::getStructure();

        $this->addElement($data['extension'], 'libraryName');
        $this->addElement($data['extension'], 'packager');
        $this->addElement($data['extension'], 'packagerUrl');

        return $data;
    }
}
