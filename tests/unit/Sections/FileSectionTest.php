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

/**
 * File Section Tests
 *
 * @package    GreenCape\Manifest
 * @subpackage Unittests
 * @author     Niels Braczek <nbraczek@bsds.de>
 * @since      Class available since Release 0.1.0
 */
class FileSectionTest extends \PHPUnit_Framework_TestCase
{
	/** @var \GreenCape\Manifest\FileSection */
	private $section = null;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->section = new \GreenCape\Manifest\FileSection();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		unset($this->section);
	}

	public function testAddFile()
	{
		$this->section->addFile('foo.txt');
		$xml = new \GreenCape\Xml\Converter(array('files' => $this->section->getStructure()));

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<files>';
		$expected .= '<filename>foo.txt</filename>';
		$expected .= '</files>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $xml);
	}

	public function testAddFileWithAttribute()
	{
		$this->section->addFile('foo.txt', array('plugin' => 'foo'));
		$xml = new \GreenCape\Xml\Converter(array('files' => $this->section->getStructure()));

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<files>';
		$expected .= '<filename plugin="foo">foo.txt</filename>';
		$expected .= '</files>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $xml);
	}

	public function testAddFolder()
	{
		$this->section->addFolder('foo');
		$xml = new \GreenCape\Xml\Converter(array('files' => $this->section->getStructure()));

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<files>';
		$expected .= '<folder>foo</folder>';
		$expected .= '</files>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $xml);
	}

	public function testAddMultiple()
	{
		$this->section->addFile('foo.txt');
		$this->section->addFile('bar.txt');
		$this->section->addFolder('foo');
		$this->section->addFolder('bar');

		$xml = new \GreenCape\Xml\Converter(array('files' => $this->section->getStructure()));

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<files>';
		$expected .= '<filename>foo.txt</filename>';
		$expected .= '<filename>bar.txt</filename>';
		$expected .= '<folder>foo</folder>';
		$expected .= '<folder>bar</folder>';
		$expected .= '</files>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $xml);
	}

	public function testRemoveFile()
	{
		$this->section->addFile('foo.txt');
		$this->section->addFile('bar.txt');
		$this->section->removeFile('foo.txt');

		$xml = new \GreenCape\Xml\Converter(array('files' => $this->section->getStructure()));

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<files>';
		$expected .= '<filename>bar.txt</filename>';
		$expected .= '</files>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $xml);
	}

	public function testRemoveFolder()
	{
		$this->section->addFolder('foo');
		$this->section->addFolder('bar');
		$this->section->removeFolder('foo');

		$xml = new \GreenCape\Xml\Converter(array('files' => $this->section->getStructure()));

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<files>';
		$expected .= '<folder>bar</folder>';
		$expected .= '</files>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $xml);
	}

}
