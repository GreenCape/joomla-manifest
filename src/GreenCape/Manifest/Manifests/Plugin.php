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
 * Plugin Manifest
 *
 * @package GreenCape\Manifest
 * @author  Niels Braczek <nbraczek@bsds.de>
 * @since   Class available since Release 0.1.0
 */
class PluginManifest extends Manifest
{
    /**
     * @var string The group name specifies for which group of plugins the new plugin is available.
     *             The existing groups are the folder names within the directory /plugins.
     *             The installer will create new folder names for group names that do not exist yet.
     */
    protected $group;

    /**
     * Constructor
     *
     * @param Converter $xml Optional XML string to preset the manifest
     */
    public function __construct(Converter $xml = null)
    {
        $this->type = 'plugin';

        if ($xml !== null) {
            $this->set($xml);
        }
    }

    /**
     * Getter and Setter
     */

    /**
     * Get the attributes for the plugin manifest
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes           = parent::getAttributes();
        $attributes['@group'] = $this->getGroup();

        return $attributes;
    }

    /**
     * Get the plugin group
     *
     * @return string The name of the group of plugins for which the new plugin is available
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Section interface
     */

    /**
     * Set the plugin group
     *
     * @param string $group The name of the group of plugins for which the new plugin is available
     *
     * @return $this This object, to provide a fluent interface
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }
}
