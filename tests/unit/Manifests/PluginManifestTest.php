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

use GreenCape\Manifest\Manifest;
use GreenCape\Manifest\PluginManifest;
use PHPUnit\Framework\TestCase;

/**
 * Plugin Manifest Tests
 *
 * @package    GreenCape\Manifest
 * @subpackage Unittests
 * @author     Niels Braczek <nbraczek@bsds.de>
 * @since      Class available since Release 0.1.0
 */
class PluginManifestTest extends TestCase
{
    /** @var PluginManifest */
    private $manifest;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->manifest = new PluginManifest();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    public function testIsManifest(): void
    {
        $this->assertInstanceOf(Manifest::class, $this->manifest);
    }

    public function testTypeIsCorrect(): void
    {
        $this->assertEquals('plugin', $this->manifest->getType());
    }

    public function testThereIsNoDefaultGroup(): void
    {
        $this->assertEmpty($this->manifest->getGroup());
    }

    public function provideGroups(): array
    {
        return [
            ['group' => 'system', 'expected' => 'system'],
            ['group' => 'user', 'expected' => 'user'],
            ['group' => 'content', 'expected' => 'content'],
        ];
    }

    /**
     * @dataProvider provideGroups
     *
     * @param $group
     * @param $expected
     */
    public function testGroupCanBeChanged($group, $expected): void
    {
        $this->manifest->setGroup($group);

        $this->assertEquals($expected, $this->manifest->getGroup());
    }

    public function testManifestRootHasGroupAttribute(): void
    {
        $this->manifest->setGroup('foo');
        $xml = (string)$this->manifest;

        $this->assertRegExp('~\<extension [^>]*group="foo"~sm', $xml);
    }
}
