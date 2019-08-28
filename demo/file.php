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
 * @package     GreenCape\Manifest
 * @subpackage  Demo
 * @author      Niels Braczek <nbraczek@bsds.de>
 * @copyright   (C) 2014 GreenCape, Niels Braczek <nbraczek@bsds.de>
 * @license     http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2.0 (GPLv2)
 * @link        http://www.greencape.com/
 * @since       File available since Release 0.1.0
 */

use GreenCape\Manifest\FileManifest;
use GreenCape\Manifest\FileSection;
use GreenCape\Manifest\FilesetSection;
use GreenCape\Manifest\SchemaSection;
use GreenCape\Manifest\ServerSection;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * This demo class reproduces the file manifest found in tests/data/file.xml.
 * It is based on the Joomla! file manifest (as of Joomla! 3.1.1). Comments were removed,
 * and the files within the fileset are moved to top to make comparision easier.
 */
class FileManifestDemo
{
	public static function getManifest()
	{
		// Create the file manifest
		$manifest = new FileManifest();

		// Meta data
		$manifest
			->setTarget('3.1')
			->setMethod('upgrade')
			->setName('files_joomla')
			->setAuthor('Joomla! Project')
			->setAuthorEmail('admin@joomla.org')
			->setAuthorUrl('www.joomla.org')
			->setCopyright('2005 - 2013', 'Open Source Matters', false)
			->setLicense('GNU General Public License version 2 or later; see LICENSE.txt')
			->setVersion('3.1.1')
			->setCreationDate('April 2013')
			->setDescription('FILES_JOOMLA_XML_DESCRIPTION');

		// Installer hooks
		$manifest
			->setScriptFile('administrator/components/com_admin/script.php');

		// SQL files
		$update = new SchemaSection();
		$update
			->addFolder('mysql', 'administrator/components/com_admin/sql/updates/mysql')
			->addFolder('sqlsrv', 'administrator/components/com_admin/sql/updates/sqlsrv')
			->addFolder('sqlazure', 'administrator/components/com_admin/sql/updates/sqlazure')
			->addFolder('postgresql', 'administrator/components/com_admin/sql/updates/postgresql');

		$manifest->addSection('update', $update);

		// Front-end files
		$files = new FileSection();
		$files
			->addFolder('administrator')
			->addFolder('cache')
			->addFolder('cli')
			->addFolder('components')
			->addFolder('images')
			->addFolder('includes')
			->addFolder('language')
			->addFolder('layouts')
			->addFolder('libraries')
			->addFolder('logs')
			->addFolder('media')
			->addFolder('modules')
			->addFolder('plugins')
			->addFolder('templates')
			->addFolder('tmp')
			->addFile('htaccess.txt')
			->addFile('web.config.txt')
			->addFile('LICENSE.txt')
			->addFile('README.txt')
			->addFile('index.php');

		$fileset = new FilesetSection();
		$fileset
			->addFileset($files);

		$manifest->addSection('fileset', $fileset);

		// Extension Update Specification
		$server = new ServerSection();
		$server
			->addServer('collection', null, 'http://update.joomla.org/core/list.xml')
			->addServer('collection', null, 'http://update.joomla.org/jed/list.xml');

		$manifest->addSection('updateservers', $server);

		return $manifest;
	}
}

/** @noinspection ForgottenDebugOutputInspection */
print_r((string) FileManifestDemo::getManifest());
