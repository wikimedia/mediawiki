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
 * @group GlobalFunctions
 * @covers ::wfParseUrl
 */
class WfParseUrlTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( 'wgUrlProtocols', [
			'//',
			'http://',
			'https://',
			'file://',
			'mailto:',
		] );
	}

	/**
	 * @dataProvider provideURLs
	 */
	public function testWfParseUrl( $url, $parts ) {
		$this->assertEquals(
			$parts,
			wfParseUrl( $url )
		);
	}

	/**
	 * Provider of URLs for testing wfParseUrl()
	 *
	 * @return array
	 */
	public static function provideURLs() {
		return [
			[
				'//example.org',
				[
					'scheme' => '',
					'delimiter' => '//',
					'host' => 'example.org',
				]
			],
			[
				'http://example.org',
				[
					'scheme' => 'http',
					'delimiter' => '://',
					'host' => 'example.org',
				]
			],
			[
				'https://example.org',
				[
					'scheme' => 'https',
					'delimiter' => '://',
					'host' => 'example.org',
				]
			],
			[
				'http://id:key@example.org:123/path?foo=bar#baz',
				[
					'scheme' => 'http',
					'delimiter' => '://',
					'user' => 'id',
					'pass' => 'key',
					'host' => 'example.org',
					'port' => 123,
					'path' => '/path',
					'query' => 'foo=bar',
					'fragment' => 'baz',
				]
			],
			[
				'file://example.org/etc/php.ini',
				[
					'scheme' => 'file',
					'delimiter' => '://',
					'host' => 'example.org',
					'path' => '/etc/php.ini',
				]
			],
			[
				'file:///etc/php.ini',
				[
					'scheme' => 'file',
					'delimiter' => '://',
					'host' => '',
					'path' => '/etc/php.ini',
				]
			],
			[
				'file:///c:/',
				[
					'scheme' => 'file',
					'delimiter' => '://',
					'host' => '',
					'path' => '/c:/',
				]
			],
			[
				'mailto:id@example.org',
				[
					'scheme' => 'mailto',
					'delimiter' => ':',
					'host' => 'id@example.org',
					'path' => '',
				]
			],
			[
				'mailto:id@example.org?subject=Foo',
				[
					'scheme' => 'mailto',
					'delimiter' => ':',
					'host' => 'id@example.org',
					'path' => '',
					'query' => 'subject=Foo',
				]
			],
			[
				'mailto:?subject=Foo',
				[
					'scheme' => 'mailto',
					'delimiter' => ':',
					'host' => '',
					'path' => '',
					'query' => 'subject=Foo',
				]
			],
			[
				'invalid://test/',
				false
			],
			// T212067
			[
				'//evil.com?example.org/foo/bar',
				[
					'scheme' => '',
					'delimiter' => '//',
					'host' => 'evil.com',
					'query' => 'example.org/foo/bar',
				]
			],
			[
				'//evil.com?example.org/foo/bar?baz#quux',
				[
					'scheme' => '',
					'delimiter' => '//',
					'host' => 'evil.com',
					'query' => 'example.org/foo/bar?baz',
					'fragment' => 'quux',
				]
			],
			[
				'//evil.com?example.org?baz#quux',
				[
					'scheme' => '',
					'delimiter' => '//',
					'host' => 'evil.com',
					'query' => 'example.org?baz',
					'fragment' => 'quux',
				]
			],
			[
				'//evil.com?example.org#quux',
				[
					'scheme' => '',
					'delimiter' => '//',
					'host' => 'evil.com',
					'query' => 'example.org',
					'fragment' => 'quux',
				]
			],
		];
	}
}
