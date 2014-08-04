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
 * Generic Manifest Tests
 *
 * @package    GreenCape\Manifest
 * @subpackage Unittests
 * @author     Niels Braczek <nbraczek@bsds.de>
 * @since      Class available since Release 0.1.0
 */
class ManifestTest extends PHPUnit_Framework_TestCase
{
	/** @var \GreenCape\Manifest\Manifest */
	private $manifest = null;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->manifest = new \GreenCape\Manifest\ComponentManifest();
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

	public function testDefaultVersionIs25()
	{
		$this->assertEquals('2.5', $this->manifest->getTarget());
	}

	public function provideVersions()
	{
		return array(
			array('version' => '1.5', 'expected' => '1.5'),
			array('version' => '2.5', 'expected' => '2.5'),
			array('version' => '3.0', 'expected' => '3.0'),
		);
	}

	/**
	 * @dataProvider provideVersions
	 * @param $version
	 * @param $expected
	 */
	public function testVersionCanBeChanged($version, $expected)
	{
		$this->manifest->setTarget($version);

		$this->assertEquals($expected, $this->manifest->getTarget());
	}

	public function testDefaultMethodIsInstall()
	{
		$this->assertEquals('install', $this->manifest->getMethod());
	}

	public function testExtensionIsManifestRoot()
	{
		$xml = (string) $this->manifest;

		$this->assertRegExp('~^\<\?xml [^>]*\?>\s*\<extension\b~sm', $xml);
	}

	public function testInstallIsLegacyManifestRoot()
	{
		$this->manifest->setTarget('1.5');

		$xml = (string) $this->manifest;

		$this->assertRegExp('~^\<\?xml [^>]*\?>\s*\<install\b~sm', $xml);
	}

	public function testNamePresetsDescription()
	{
		$this->manifest->setName('com_foo');

		$this->assertEquals('COM_FOO_XML_DESCRIPTION', $this->manifest->getDescription());
	}

	public function testAuthorPresetsCopyrightOwner()
	{
		$this->manifest->setAuthor('Any Name');

		$this->assertRegExp('~Any Name\. All rights reserved\.~', $this->manifest->getCopyright());
	}

	public function testDefaultCreationDateIsToday()
	{
		$this->manifest->setCreationDate();

		$this->assertEquals(date('F Y'), $this->manifest->getCreationDate());
	}

	public function testCreationDateTriggersCopyrightYearRange()
	{
		$this->manifest->setAuthor('Sean Connery');
		$this->manifest->setCreationDate('02.08.2010');

		$this->assertRegExp('~\(C\) 2010 - \d+ Sean Connery\. All rights reserved\.~', $this->manifest->getCopyright());
	}

	public function testAddFileSection()
	{
		$files = new \GreenCape\Manifest\FileSection();
		$files->setBase('site');
		$files->addFile('foo.php');
		$files->addFolder('bar');

		$this->manifest->addSection('files', $files);

		$this->assertRegExp('~\<files folder="site">\s*\<filename>foo\.php\</filename>\s*\<folder>bar\</folder>\s*\</files>~sm', (string) $this->manifest);
	}

	public function testAttributesDoNotFlowIntoSiblings()
	{
		$files = new \GreenCape\Manifest\FileSection();
		$files->setBase('site');
		$files->addFile('foo.php');
		$files->addFolder('bar');

		$this->manifest->addSection('files', $files);

		preg_match_all('~\<(\w+)[^>]*folder="site"~sm', (string) $this->manifest, $matches, PREG_SET_ORDER);
		$this->assertEquals(1, count($matches));
		$this->assertEquals('files', $matches[0][1]);
	}

	public function testAddMediaSection()
	{
		$files = new \GreenCape\Manifest\MediaSection();
		$files->setBase('media');
		$files->setDestination('com_foo');
		$files->addFile('foo.php');
		$files->addFolder('bar');

		$this->manifest->addSection('media', $files);

		$this->assertRegExp('~\<media folder="media" destination="com_foo">\s*\<filename>foo\.php\</filename>\s*\<folder>bar\</folder>\s*\</media>~sm', (string) $this->manifest);
	}
}
