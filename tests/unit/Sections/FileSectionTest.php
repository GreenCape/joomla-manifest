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
 * File Section Tests
 *
 * @package    GreenCape\Manifest
 * @subpackage Unittests
 * @author     Niels Braczek <nbraczek@bsds.de>
 * @since      Class available since Release 0.1.0
 */
class FileSectionTest extends PHPUnit_Framework_TestCase
{
	/** @var GreenCape\Manifest\FileSection */
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