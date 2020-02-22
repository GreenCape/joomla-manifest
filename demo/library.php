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
use GreenCape\Manifest\LibraryManifest;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * This demo class reproduces the library manifest found in tests/data/library.xml.
 * It is based on the Joomla! library manifest (as of Joomla! 3.1.1).
 * Default values were added.
 */
class LibraryManifestDemo
{
    public static function getManifest(): LibraryManifest
    {
        // Create the library manifest
        $manifest = new LibraryManifest();

        // Meta data
        $manifest
            ->setTarget('3.1')
            ->setMethod('install')
            ->setName('Joomla! Platform')
            ->setLibraryName('joomla')
            ->setVersion('12.2')
            ->setDescription('LIB_JOOMLA_XML_DESCRIPTION')
            ->setCreationDate('January 2008')
            ->setCopyright('2005 - 2013', 'Open Source Matters', false)
            ->setLicense('http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL')
            ->setAuthor('Joomla! Project')
            ->setAuthorEmail('admin@joomla.org')
            ->setAuthorUrl('http://www.joomla.org')
            ->setPackager('Joomla!')
            ->setPackagerUrl('http://www.joomla.org')
        ;

        // Front-end files
        $files = new FileSection();
        $files
            ->setBase('libraries')
            ->setFileTag('file')
            ->addFolder('compat')
            ->addFolder('joomla')
            ->addFolder('legacy')
            ->addFile('import.legacy.php')
            ->addFile('import.php')
            ->addFile('loader.php')
            ->addFile('platform.php')
        ;

        $manifest->addSection('files', $files);

        return $manifest;
    }
}

/** @noinspection ForgottenDebugOutputInspection */
print_r((string)LibraryManifestDemo::getManifest());
