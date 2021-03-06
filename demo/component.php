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

use GreenCape\Manifest\AdminSection;
use GreenCape\Manifest\ComponentManifest;
use GreenCape\Manifest\DependencySection;
use GreenCape\Manifest\FileSection;
use GreenCape\Manifest\LanguageSection;
use GreenCape\Manifest\MediaSection;
use GreenCape\Manifest\MenuSection;
use GreenCape\Manifest\SchemaSection;
use GreenCape\Manifest\ServerSection;
use GreenCape\Manifest\SqlSection;
use GreenCape\Manifest\TableSection;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * This demo class reproduces the sample manifest found in tests/data/com_alpha.xml.
 * It is based on the Joomla! test data (as of August 2014). Comments were removed,
 * and the copyright statement was supplemented with 'All rights reserved.'
 *
 * For the commented original,
 *
 * @see http://svn.joomla.org/project/cms/development/trunk/tests/_data/installer_packages/com_alpha/alpha.xml
 */
class ComponentManifestDemo
{
    public static function getManifest(): ComponentManifest
    {
        // Create the component manifest
        $manifest = new ComponentManifest();

        // Meta data
        $manifest
            ->setTarget('1.6')
            ->setMethod('upgrade')
            ->setName('com_alpha')
            ->setAuthor('John Doe')
            ->setAuthorEmail('john.doe@example.org')
            ->setAuthorUrl('http://www.example.org')
            ->setCopyright(2008, 'Copyright Info', false)
            ->setLicense('License Info')
            ->setVersion('1.0')
            ->setCreationDate('March 2006')
        ;

        // Installer hooks
        $manifest
            ->setInstallFile('file.install.php')
            ->setUninstallFile('file.uninstall.php')
            ->setScriptFile('file.script.php')
        ;

        // SQL files
        $install = new SqlSection();
        $install
            ->addFile('mysql', 'sql/install.mysql.utf8.sql', ['charset' => 'utf8']);

        $manifest->addSection('install', $install);

        $uninstall = new SqlSection();
        $uninstall
            ->addFile('mysql', 'sql/uninstall.mysql.utf8.sql', ['charset' => 'utf8', 'folder' => 'sql']);

        $manifest->addSection('uninstall', $uninstall);

        $update = new SchemaSection();
        $update
            ->addFolder('mysql', 'sql/updates/mysql')
            ->addFolder('sqlsrv', 'sql/updates/sqlsrv')
        ;

        $manifest->addSection('update', $update);

        // Front-end files
        $files = new FileSection();
        $files
            ->setBase('site')
            ->addFile('alpha.php')
        ;

        $manifest->addSection('files', $files);

        // Front-end language (legacy 1.5 support)
        $language = new LanguageSection();
        $language
            ->setBase('site')
            ->addFile('en-GB', 'language/en-GB/en-GB.com_alpha.ini')
        ;

        $manifest->addSection('languages', $language);

        // Media Files
        $media = new MediaSection();
        $media
            ->setDestination('com_alpha')
            ->addFile('com_alpha.jpg')
        ;

        $manifest->addSection('media', $media);

        // Backend files
        $menu = new MenuSection();
        $menu
            ->setLabel('Alpha')
            ->setIcon('components/com_alpha/applications-internet-16.png')
            ->addMenu('Installer', 'option=com_installer')
            ->addMenu('Users', 'option=com_users')
        ;

        $adminFiles = new FileSection();
        $adminFiles
            ->setBase('admin')
            ->addFile('admin.alpha.php')
            ->addFile('image.png')
            ->addFile('applications-internet.png')
            ->addFile('applications-internet-16.png')
            ->addFolder('sql')
        ;

        $adminLanguage = new LanguageSection();
        $adminLanguage
            ->setBase('admin/language')
            ->addFile('en-GB', 'en-GB/en-GB.com_alpha.ini')
            ->addFile('en-GB', 'en-GB/en-GB.com_alpha.sys.ini')
        ;

        $admin = new AdminSection();
        $admin
            ->setMenu($menu)
            ->setFiles($adminFiles)
            ->setLanguage($adminLanguage)
        ;

        $manifest->addSection('administration', $admin);

        // Extension Update Specification
        $server = new ServerSection();
        $server
            ->addServer(
                'extension',
                'Extension Update Site',
                'http://jsitepoint.com/update/components/com_alpha/extension.xml',
                1
            )
            ->addServer(
                'collection',
                'Collection Update Site',
                'http://jsitepoint.com/update/update.xml',
                2
            )
        ;

        $manifest->addSection('updateservers', $server);

        // Tables
        $tables = new TableSection();
        $tables
            ->addTable('#__alpha_install')
            ->addTable('#__alpha_update', true)
        ;

        $manifest->addSection('tables', $tables);

        // Dependencies
        $dependencies = new DependencySection();
        $dependencies
            ->addDependency('platform', 'joomla', '=', '1.5');

        $manifest->addSection('dependencies', $dependencies);

        return $manifest;
    }
}

/** @noinspection ForgottenDebugOutputInspection */
print_r((string)ComponentManifestDemo::getManifest());
