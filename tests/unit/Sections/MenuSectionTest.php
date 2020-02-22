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
 * @package         GreenCape\ManifestTest
 * @subpackage      Unittests
 * @author          Niels Braczek <nbraczek@bsds.de>
 * @copyright   (C) 2014-2015 GreenCape, Niels Braczek <nbraczek@bsds.de>
 * @license         http://opensource.org/licenses/MIT The MIT license (MIT)
 * @link            http://greencape.github.io
 * @since           File available since Release 0.1.0
 */

namespace GreenCape\ManifestTest;

use GreenCape\Manifest\MenuSection;
use GreenCape\Xml\Converter;
use PHPUnit\Framework\TestCase;

/**
 * Menu Section Tests
 *
 * @package    GreenCape\Manifest
 * @subpackage Unittests
 * @author     Niels Braczek <nbraczek@bsds.de>
 * @since      Class available since Release 0.1.0
 */
class MenuSectionTest extends TestCase
{
    /** @var MenuSection */
    private $section;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->section = new MenuSection();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        unset($this->section);
    }

    /**
     * Issue #1
     */
    public function testEmptySubmenusAreOmitted(): void
    {
        $this->section->setLabel('Menu');
        $this->section->setIcon('Icon');
        $xml = new Converter(['administration' => $this->section->getStructure()]);

        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<administration>';
        $expected .= '<menu img="Icon">Menu</menu>';
        $expected .= '</administration>';

        $this->assertXmlStringEqualsXmlString($expected, (string)$xml);
    }

    public function testMenuSectionWorksAsSubmenu(): void
    {
        $submenu1 = new MenuSection();
        $submenu1
            ->setLabel('submenu 1')
            ->setIcon('icon1')
        ;

        $submenu2 = new MenuSection();
        $submenu2
            ->setLabel('submenu 2')
            ->setIcon('icon2')
        ;

        $this->section->setLabel('Menu');
        $this->section->setIcon('Icon');
        $this->section->addMenu($submenu1);
        $this->section->addMenu($submenu2);
        $xml = new Converter(['administration' => $this->section->getStructure()]);

        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<administration>';
        $expected .= '<menu img="Icon">Menu</menu>';
        $expected .= '<submenu>';
        $expected .= '<menu img="icon1">submenu 1</menu>';
        $expected .= '<menu img="icon2">submenu 2</menu>';
        $expected .= '</submenu>';
        $expected .= '</administration>';

        $this->assertXmlStringEqualsXmlString($expected, (string)$xml);
    }

    /**
     * Issue #3, issue #6
     */
    public function testMenuHasMissingAttributes(): void
    {
        $submenu1 = new MenuSection();
        $submenu1
            ->setLabel('menu1')
            ->setIcon('icon1')
            ->setAlt('alt1')
            ->setLink('link1')
            ->setAttribute('view', 'view1')
        ;

        $this->section
            ->setLabel('menu')
            ->setIcon('icon')
            ->setAlt('alt')
            ->setLink('link')
            ->setAttribute('view', 'view')
            ->setAttribute('foo', 'bar')
            ->addMenu($submenu1)
        ;
        $xml = new Converter(['administration' => $this->section->getStructure()]);

        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<administration>';
        $expected .= '<menu link="link" view="view" img="icon" alt="alt" foo="bar">menu</menu>';
        $expected .= '<submenu>';
        $expected .= '<menu link="link1" view="view1" img="icon1" alt="alt1">menu1</menu>';
        $expected .= '</submenu>';
        $expected .= '</administration>';

        $this->assertXmlStringEqualsXmlString($expected, (string)$xml);
    }
}
