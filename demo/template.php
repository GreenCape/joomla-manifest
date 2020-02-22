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
 * @package         GreenCape\Manifest
 * @subpackage      Demo
 * @author          Niels Braczek <nbraczek@bsds.de>
 * @copyright   (C) 2014 GreenCape, Niels Braczek <nbraczek@bsds.de>
 * @license         http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2.0 (GPLv2)
 * @link            http://www.greencape.com/
 * @since           File available since Release 0.1.0
 */

use GreenCape\Manifest\FileSection;
use GreenCape\Manifest\TemplateManifest;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * This demo class reproduces the sample manifest found in tests/data/templateDetails.xml.
 * It is based on the Joomla! test data (as of August 2014).
 * The copyright statement was supplemented with 'All rights reserved.'
 *
 * For the original,
 *
 * @see http://svn.joomla.org/project/cms/development/trunk/tests/_data/installer_packages/tpl_simple/templateDetails.xml
 */
class TemplateManifestDemo
{
    public static function getManifest(): TemplateManifest
    {
        // Create the template manifest
        $manifest = new TemplateManifest();

        // Meta data
        $manifest
            ->setTarget('1.6')
            ->setMethod('upgrade')
            ->setName('simple_template')
            ->setCreationDate('July 2008')
            ->setAuthor('John Doe')
            ->setAuthorEmail('john.doe@example.org')
            ->setAuthorUrl('http://www.example.org')
            ->setCopyright(2008, 'Copyright Info', false)
            ->setLicense('License Info')
            ->setVersion('1.6.0')
            ->setDescription('TPL_TPL_SIMPLE_XML_DESCRIPTION')
        ;

        // Front-end files
        $files = new FileSection();
        $files
            ->addFile('index.php');

        $manifest->addSection('files', $files);

        return $manifest;
    }
}

/** @noinspection ForgottenDebugOutputInspection */
print_r((string)TemplateManifestDemo::getManifest());
