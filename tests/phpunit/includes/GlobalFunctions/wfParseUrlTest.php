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
class WfParseUrlTest extends MediaWikiIntegrationTestCase {
	protected function setUp(): void {
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
	 */
	public static function provideURLs() {
		// Same tests as the UrlUtils method to ensure they don't fall out of sync
		return UrlUtilsTest::provideParse();
	}
}
