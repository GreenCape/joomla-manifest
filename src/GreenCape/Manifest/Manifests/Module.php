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
 * Module Manifest
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class ModuleManifest extends Manifest
{
    /**
     * @var string The client attribute allows you to specify for which application client the new module is available.
     */
    protected $client = 'site';

    /**
     * Constructor
     *
     * @param Converter $xml Optional XML string to preset the manifest
     */
    public function __construct(Converter $xml = null)
    {
        $this->type        = 'module';
        $this->map['help'] = 'VerbatimSection';

        if ($xml !== null) {
            $this->set($xml);
        }
    }

    /**
     * Getter and Setter
     */

    /**
     * Get the help key
     *
     * @return string The help key
     */
    public function getHelp()
    {
        $attribute = $this->sections['help']->getAttributes();

        return $attribute['@key'];
    }

    /**
     * Set the help key
     *
     * @param string $help The help key
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setHelp($help)
    {
        $this->sections['help'] = new VerbatimSection(['help' => null, '@key' => $help]);

        return $this;
    }

    /**
     * Get the attributes for the module manifest
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes            = parent::getAttributes();
        $attributes['@client'] = $this->getClient();

        return $attributes;
    }

    /**
     * Get the name of the client application
     *
     * @return string Name of the which application client for which the new module is available
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Section interface
     */

    /**
     * Set the name of the client application
     *
     * @param string $client Name of the which application client for which the new module is available
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }
}
