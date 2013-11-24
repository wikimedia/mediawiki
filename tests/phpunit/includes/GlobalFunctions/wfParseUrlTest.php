<?php
/**
 * Copyright © 2013 Alexandre Emsenhuber
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * @covers ::wfParseUrl
 */
class WfParseUrlTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( 'wgUrlProtocols', array(
			'//', 'http://', 'file://', 'mailto:',
		) );
	}

	/**
	 * @dataProvider provideURLs
	 */
	public function testWfParseUrl( $url, $parts ) {
		$partsDump = var_export( $parts, true );
		$this->assertEquals(
			$parts,
			wfParseUrl( $url ),
			"Testing $url parses to $partsDump"
		);
	}

	/**
	 * Provider of URLs for testing wfParseUrl()
	 *
	 * @return array
	 */
	public static function provideURLs() {
		return array(
			array(
				'//example.org',
				array(
					'scheme' => '',
					'delimiter' => '//',
					'host' => 'example.org',
				)
			),
			array(
				'http://example.org',
				array(
					'scheme' => 'http',
					'delimiter' => '://',
					'host' => 'example.org',
				)
			),
			array(
				'http://id:key@example.org:123/path?foo=bar#baz',
				array(
					'scheme' => 'http',
					'delimiter' => '://',
					'user' => 'id',
					'pass' => 'key',
					'host' => 'example.org',
					'port' => 123,
					'path' => '/path',
					'query' => 'foo=bar',
					'fragment' => 'baz',
				)
			),
			array(
				'file://example.org/etc/php.ini',
				array(
					'scheme' => 'file',
					'delimiter' => '://',
					'host' => 'example.org',
					'path' => '/etc/php.ini',
				)
			),
			array(
				'file:///etc/php.ini',
				array(
					'scheme' => 'file',
					'delimiter' => '://',
					'host' => '',
					'path' => '/etc/php.ini',
				)
			),
			array(
				'file:///c:/',
				array(
					'scheme' => 'file',
					'delimiter' => '://',
					'host' => '',
					'path' => '/c:/',
				)
			),
			array(
				'mailto:id@example.org',
				array(
					'scheme' => 'mailto',
					'delimiter' => ':',
					'host' => 'id@example.org',
					'path' => '',
				)
			),
			array(
				'mailto:id@example.org?subject=Foo',
				array(
					'scheme' => 'mailto',
					'delimiter' => ':',
					'host' => 'id@example.org',
					'path' => '',
					'query' => 'subject=Foo',
				)
			),
			array(
				'mailto:?subject=Foo',
				array(
					'scheme' => 'mailto',
					'delimiter' => ':',
					'host' => '',
					'path' => '',
					'query' => 'subject=Foo',
				)
			),
			array(
				'invalid://test/',
				false
			),
		);
	}
}
