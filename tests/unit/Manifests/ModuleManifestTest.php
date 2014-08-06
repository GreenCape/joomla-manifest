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
 * Module Manifest Tests
 *
 * @package    GreenCape\Manifest
 * @subpackage Unittests
 * @author     Niels Braczek <nbraczek@bsds.de>
 * @since      Class available since Release 0.1.0
 */
class ModuleManifestTest extends PHPUnit_Framework_TestCase
{
	/** @var \GreenCape\Manifest\ModuleManifest */
	private $manifest = null;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->manifest = new \GreenCape\Manifest\ModuleManifest();
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
		$this->assertEquals('module', $this->manifest->getType());
	}

	public function testDefaultClientIsSite()
	{
		$this->assertEquals('site', $this->manifest->getClient());
	}

	public function provideClients()
	{
		return array(
			array('client' => 'site', 'expected' => 'site'),
			array('client' => 'administrator', 'expected' => 'administrator'),
		);
	}

	/**
	 * @dataProvider provideClients
	 * @param $client
	 * @param $expected
	 */
	public function testClientCanBeChanged($client, $expected)
	{
		$this->manifest->setClient($client);

		$this->assertEquals($expected, $this->manifest->getClient());
	}

	public function testManifestRootHasClientAttribute()
	{
		$xml = (string) $this->manifest;

		$this->assertRegExp('~\<extension [^>]*client="site"~sm', $xml);
	}

	public function testReproduceSample()
	{
		ob_start();
		include_once __DIR__ . '/../../../demo/module.php';
		ob_clean();

		$expected = new \GreenCape\Xml\Converter(__DIR__ . '/../../data/mod_alpha.xml');
		$this->sort($expected->data['extension']);

		$manifest = new \GreenCape\Xml\Converter((string) ModuleManifestDemo::getManifest());
		$this->sort($manifest->data['extension']);

		$this->assertEquals($expected->data, $manifest->data);
	}

	private function sort(&$manifestData)
	{
		usort($manifestData, function ($a, $b)
		{
			reset($a);
			$nameA = key($a);
			reset($b);
			$nameB = key($b);

			if ($nameA == $nameB)
			{
				return 0;
			}
			return $nameA < $nameB ? -1 : 1;
		});
	}
}
