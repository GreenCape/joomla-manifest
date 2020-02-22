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
    protected $tag;

    /** @var  int */
    protected $rtl;

    /** @var  string */
    protected $locale;

    /** @var  string */
    protected $codePage;

    /** @var  string */
    protected $backwardLanguage;

    /** @var  string */
    protected $fontName;

    /** @var  int */
    protected $firstDay = 0;

    /**
     * Constructor
     *
     * @param Converter $xml Optional XML string to preset the manifest
     */
    public function __construct(Converter $xml = null)
    {
        $this->type                 = 'language';
        $this->sections['metadata'] = [];

        if ($xml !== null) {
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
     * Get the manifest structure
     *
     * @return array
     */
    public function getStructure()
    {
        $data = $this->getManifestRoot('metafile');

        $this->addMetadata($data['metafile']);
        $this->addElement($data['metafile'], 'tag');

        foreach ($this->sections as $tag => $section) {
            $element = [];

            if ($section instanceof Section) {
                $element[$tag] = $section->getStructure();

                foreach ($section->getAttributes() as $attribute => $value) {
                    $element[$attribute] = $value;
                }
            } else {
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
        $attributes             = [];
        $attributes['@version'] = $this->getTarget();
        $attributes['@client']  = $this->getClient();

        return $attributes;
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
        $this->tag                         = $tag;
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
        $this->fontName                            = $fontName;
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
        $this->locale                         = $locale;
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
        return (bool)$this->rtl;
    }

    /**
     * Set RTL value
     *
     * @param boolean $rtl true = RTL, false = LTR
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setRtl($rtl)
    {
        $this->rtl                         = $rtl ? 1 : 0;
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
        $this->codePage                            = $page;
        $this->sections['metadata']['winCodePage'] = $page;

        return $this;
    }

    /**
     * Get the first day of week
     *
     * @return string The first day of week
     */
    public function getFirstDay()
    {
        return $this->firstDay;
    }

    /**
     * Set the first day of week
     *
     * @param string $dayNum The first day of week
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setFirstDay($dayNum)
    {
        $this->firstDay                         = $dayNum;
        $this->sections['metadata']['firstDay'] = $dayNum;

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
        foreach ($data as $entry) {
            foreach ($entry as $key => $value) {
                $this->sections['metadata'][$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Section interface
     */

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
        $this->backwardLanguage                     = $backwardLanguage;
        $this->sections['metadata']['backwardLang'] = $backwardLanguage;

        return $this;
    }
}
