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
 * @subpackage  Unittests
 * @author      Niels Braczek <nbraczek@bsds.de>
 * @copyright   (C) 2014 GreenCape, Niels Braczek <nbraczek@bsds.de>
 * @license     http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2.0 (GPLv2)
 * @link        http://www.greencape.com/
 * @since       File available since Release 0.1.0
 */

/**
 * File Manifest Tests
 *
 * @package    GreenCape\Manifest
 * @subpackage Unittests
 * @author     Niels Braczek <nbraczek@bsds.de>
 * @since      Class available since Release 0.1.0
 */
class FileManifestTest extends PHPUnit_Framework_TestCase
{
	/** @var \GreenCape\Manifest\Manifest */
	private $manifest = null;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->manifest = new \GreenCape\Manifest\FileManifest();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	public function testIsManifest()
	{
		$this->assertInstanceOf('GreenCape\\Manifest\\Manifest', $this->manifest);
	}

	public function testTypeIsCorrect()
	{
		$this->assertEquals('file', $this->manifest->getType());
	}

	/**
	 * Issue #9: File manifest: fileset is not supported
	 */
	public function testAddFileset()
	{
		$this->manifest
			->setAuthor('Test')
			->setCreationDate('August 2014')
			->setCopyright('2014', 'Test', false);

		$section = new \GreenCape\Manifest\FilesetSection();

		$files = new \GreenCape\Manifest\FileSection();
		$files
			->setBase('dir')
			->addFile('foo.txt');

		$section->addFileset($files);

		$this->manifest->addSection('fileset', $section);

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<extension method="install" type="file" version="2.5">';
		$expected .= '<author>Test</author>';
		$expected .= '<creationDate>August 2014</creationDate>';
		$expected .= '<copyright>(C) 2014 Test. All rights reserved.</copyright>';
		$expected .= '<license>GNU General Public License version 2 or later; see LICENSE.txt</license>';
		$expected .= '<fileset>';
		$expected .= '<files folder="dir">';
		$expected .= '<file>foo.txt</file>';
		$expected .= '</files>';
		$expected .= '</fileset>';
		$expected .= '</extension>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $this->manifest);
	}

}
