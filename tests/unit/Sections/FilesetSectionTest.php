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

use GreenCape\Manifest\FileSection;
use GreenCape\Manifest\FilesetSection;
use GreenCape\Xml\Converter;
use PHPUnit\Framework\TestCase;

/**
 * Fileset Section Tests
 *
 * @package    GreenCape\Manifest
 * @subpackage Unittests
 * @author     Niels Braczek <nbraczek@bsds.de>
 * @since      Class available since Release 0.1.0
 */
class FilesetSectionTest extends TestCase
{
    /** @var FilesetSection */
    private $section;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->section = new FilesetSection();
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
     * Issue #9: File manifest: fileset is not supported
     */
    public function testAddFileSection(): void
    {
        $files = new FileSection();
        $files
            ->setBase('dir')
            ->addFile('foo.txt')
        ;

        $this->section->addFileset($files);
        $xml = new Converter(['fileset' => $this->section->getStructure()]);

        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<fileset>';
        $expected .= '<files folder="dir">';
        $expected .= '<file>foo.txt</file>';
        $expected .= '</files>';
        $expected .= '</fileset>';

        $this->assertXmlStringEqualsXmlString($expected, (string)$xml);
    }

    /**
     * Issue #9: File manifest: fileset is not supported
     */
    public function testAddMultipleFileSections(): void
    {
        $files = new FileSection();
        $files
            ->setBase('dir')
            ->addFile('foo.txt')
        ;

        $this->section->addFileset($files);

        $files = new FileSection();
        $files
            ->setBase('another/dir')
            ->addFile('bar.txt')
        ;

        $this->section->addFileset($files);

        $xml = new Converter(['fileset' => $this->section->getStructure()]);

        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<fileset>';
        $expected .= '<files folder="dir">';
        $expected .= '<file>foo.txt</file>';
        $expected .= '</files>';
        $expected .= '<files folder="another/dir">';
        $expected .= '<file>bar.txt</file>';
        $expected .= '</files>';
        $expected .= '</fileset>';

        $this->assertXmlStringEqualsXmlString($expected, (string)$xml);
    }
}
