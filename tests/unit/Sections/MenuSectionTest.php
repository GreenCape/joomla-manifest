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
 * Menu Section Tests
 *
 * @package    GreenCape\Manifest
 * @subpackage Unittests
 * @author     Niels Braczek <nbraczek@bsds.de>
 * @since      Class available since Release 0.1.0
 */
class MenuSectionTest extends PHPUnit_Framework_TestCase
{
	/** @var GreenCape\Manifest\MenuSection */
	private $section = null;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->section = new \GreenCape\Manifest\MenuSection();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		unset($this->section);
	}

	/**
	 * Issue #1
	 */
	public function testEmptySubmenusAreOmitted()
	{
		$this->section->setLabel('Menu');
		$this->section->setIcon('Icon');
		$xml = new \GreenCape\Xml\Converter(array('administration' => $this->section->getStructure()));

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<administration>';
		$expected .= '<menu img="Icon">Menu</menu>';
		$expected .= '</administration>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $xml);
	}

	public function testMenuSectionWorksAsSubmenu()
	{
		$submenu1 = new \GreenCape\Manifest\MenuSection();
		$submenu1
			->setLabel('submenu 1')
			->setIcon('icon1');

		$submenu2 = new \GreenCape\Manifest\MenuSection();
		$submenu2
			->setLabel('submenu 2')
			->setIcon('icon2');

		$this->section->setLabel('Menu');
		$this->section->setIcon('Icon');
		$this->section->addMenu($submenu1);
		$this->section->addMenu($submenu2);
		$xml = new \GreenCape\Xml\Converter(array('administration' => $this->section->getStructure()));

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<administration>';
		$expected .= '<menu img="Icon">Menu</menu>';
		$expected .= '<submenu>';
		$expected .= '<menu img="icon1">submenu 1</menu>';
		$expected .= '<menu img="icon2">submenu 2</menu>';
		$expected .= '</submenu>';
		$expected .= '</administration>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $xml);
	}

	/**
	 * Issue #3, issue #6
	 */
	public function testMenuHasMissingAttributes()
	{
		$submenu1 = new \GreenCape\Manifest\MenuSection();
		$submenu1
			->setLabel('menu1')
			->setIcon('icon1')
			->setAlt('alt1')
			->setLink('link1')
			->setAttribute('view', 'view1');

		$this->section
			->setLabel('menu')
			->setIcon('icon')
			->setAlt('alt')
			->setLink('link')
			->setAttribute('view', 'view')
			->setAttribute('foo', 'bar')
			->addMenu($submenu1);
		$xml = new \GreenCape\Xml\Converter(array('administration' => $this->section->getStructure()));

		$expected = '<?xml version="1.0" encoding="UTF-8"?>';
		$expected .= '<administration>';
		$expected .= '<menu link="link" view="view" img="icon" alt="alt" foo="bar">menu</menu>';
		$expected .= '<submenu>';
		$expected .= '<menu link="link1" view="view1" img="icon1" alt="alt1">menu1</menu>';
		$expected .= '</submenu>';
		$expected .= '</administration>';

		$this->assertXmlStringEqualsXmlString($expected, (string) $xml);
	}
}
