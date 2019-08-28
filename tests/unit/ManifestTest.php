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
 * @package     GreenCape\ManifestTest
 * @subpackage  Unittests
 * @author      Niels Braczek <nbraczek@bsds.de>
 * @copyright   (C) 2014-2015 GreenCape, Niels Braczek <nbraczek@bsds.de>
 * @license     http://opensource.org/licenses/MIT The MIT license (MIT)
 * @link        http://greencape.github.io
 * @since       File available since Release 0.1.0
 */

namespace GreenCape\ManifestTest;

use GreenCape\Manifest\ComponentManifest;
use GreenCape\Manifest\FileSection;
use GreenCape\Manifest\Manifest;
use GreenCape\Manifest\MediaSection;
use GreenCape\Xml\Converter;
use PHPUnit_Framework_TestCase;

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
	/** @var Manifest */
	private $manifest;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->manifest = new ComponentManifest();
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
		$files = new FileSection();
		$files->setBase('site');
		$files->addFile('foo.php');
		$files->addFolder('bar');

		$this->manifest->addSection('files', $files);

		$this->assertRegExp('~\<files folder="site">\s*\<filename>foo\.php\</filename>\s*\<folder>bar\</folder>\s*\</files>~sm', (string) $this->manifest);
	}

	public function testAttributesDoNotFlowIntoSiblings()
	{
		$files = new FileSection();
		$files->setBase('site');
		$files->addFile('foo.php');
		$files->addFolder('bar');

		$this->manifest->addSection('files', $files);

		preg_match_all('~<(\w+)[^>]*folder="site"~m', (string) $this->manifest, $matches, PREG_SET_ORDER);
		$this->assertCount(1, $matches);
		$this->assertEquals('files', $matches[0][1]);
	}

	public function testAddMediaSection()
	{
		$files = new MediaSection();
		$files->setBase('media');
		$files->setDestination('com_foo');
		$files->addFile('foo.php');
		$files->addFolder('bar');

		$this->manifest->addSection('media', $files);

		$this->assertRegExp('~\<media folder="media" destination="com_foo">\s*\<filename>foo\.php\</filename>\s*\<folder>bar\</folder>\s*\</media>~sm', (string) $this->manifest);
	}

	/**
	 * Issue #4: method attribute is always set to 'install'
	 */
	public function testMethodAttributeIsImportedCorrectly()
	{

		$xml = Manifest::load(__DIR__ . '/../data/issue#4.xml');

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<extension type="component" version="1.6" method="upgrade">';
		$expected .= '<name>com_alpha</name>';
		$expected .= '<author>John Doe</author>';
		$expected .= '<creationDate>March 2006</creationDate>';
		$expected .= '<copyright>(C) 2008 - ' . date('Y') . ' Copyright Info. All rights reserved.</copyright>';
		$expected .= '<license>License Info</license>';
		$expected .= '<description>COM_ALPHA_XML_DESCRIPTION</description>';
		$expected .= '</extension>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $xml);
	}

	public function provideSampleFiles()
	{
		return array(
			array('component', 'com_alpha.xml', 'extension'),
			array('module', 'mod_alpha.xml', 'extension'),
			array('plugin', 'plg_system_alpha.xml', 'extension'),
			array('language', 'xx-XX.xml', 'metafile'),
			array('template', 'templateDetails.xml', 'extension'),
			array('file', 'file.xml', 'extension'),
			array('library', 'library.xml', 'extension'),
			array('package', 'pkg_joomla.xml', 'extension'),
		);
	}

	/**
	 * @dataProvider provideSampleFiles
	 *
	 * @param string $demo    The demo script
	 * @param string $xml     The sample XML
	 * @param string $rootTag The root tag in the sample file
	 */
	public function testReproduceSample($demo, $xml, $rootTag)
	{
		$demoFile  = $demo . '.php';
		$demoClass = ucfirst($demo) . 'ManifestDemo';
		ob_start();
		/** @noinspection PhpIncludeInspection */
		include_once __DIR__ . '/../../demo/' . $demoFile;
		ob_get_clean();

		$expected = new Converter(__DIR__ . '/../data/'. $xml);
		$this->sort($expected->data[$rootTag]);

		$manifest = new Converter((string) call_user_func(array($demoClass, 'getManifest')));
		$this->sort($manifest->data[$rootTag]);

		$this->assertEquals($expected->data, $manifest->data);
	}

	private function sort(&$manifestData)
	{
		usort($manifestData, static function ($a, $b)
		{
			reset($a);
			$nameA = key($a);
			reset($b);
			$nameB = key($b);

			if ($nameA === $nameB)
			{
				return 0;
			}
			return $nameA < $nameB ? -1 : 1;
		});
	}

	/**
	 * Issue #2: Line feeds in data fields should be removed
	 */
	public function testLineFeedsInDataFieldsAreRemoved()
	{

		$xml = Manifest::load(__DIR__ . '/../data/issue#2.xml');

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<extension type="component" version="1.6" method="upgrade">';
		$expected .= '<name>com_alpha</name>';
		$expected .= '<author>John Doe</author>';
		$expected .= '<creationDate>March 2006</creationDate>';
		$expected .= '<copyright>(C) 2008 - ' . date('Y') . ' Copyright Info. All rights reserved.</copyright>';
		$expected .= '<license>License Info</license>';
		$expected .= '<description>COM_ALPHA_XML_DESCRIPTION</description>';
		$expected .= '</extension>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $xml);
	}

	public function testGetSection()
	{
		$manifest = Manifest::load(__DIR__ . '/../data/plg_system_alpha.xml');

		$this->assertInstanceOf('\\GreenCape\\Manifest\\FileSection', $manifest->getSection('files'));
	}
}
