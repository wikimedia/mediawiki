<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@member.fsf.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

/**
 * @covers ExtensionJsonValidator
 */
class ExtensionJsonValidatorTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideValidate
	 */
	public function testValidate( $file, $expected ) {
		// If a dependency is missing, skip this test.
		$validator = new ExtensionJsonValidator( function ( $msg ) {
			$this->markTestSkipped( $msg );
		} );

		if ( is_string( $expected ) ) {
			$this->setExpectedException(
				ExtensionJsonValidationError::class,
				$expected
			);
		}

		$dir = __DIR__ . '/../../data/registration/';
		$this->assertSame(
			$expected,
			$validator->validate( $dir . $file )
		);
	}

	public function provideValidate() {
		return [
			[
				'notjson.txt',
				'notjson.txt is not valid JSON'
			],
			[
				'duplicate_keys.json',
				'Duplicate key: name'
			],
			[
				'no_manifest_version.json',
				'no_manifest_version.json does not have manifest_version set.'
			],
			[
				'old_manifest_version.json',
				'old_manifest_version.json is using a non-supported schema version'
			],
			[
				'newer_manifest_version.json',
				'newer_manifest_version.json is using a non-supported schema version'
			],
			[
				'bad_spdx.json',
				"bad_spdx.json did not pass validation.
[license-name] Invalid SPDX license identifier, see <https://spdx.org/licenses/>"
			],
			[
				'invalid.json',
				"invalid.json did not pass validation.
[license-name] Array value found, but a string is required"
			],
			[
				'good.json',
				true
			],
			[
				'bad_url.json', 'bad_url.json did not pass validation.
[url] Should use HTTPS for www.mediawiki.org URLs'
			],
			[
				'bad_url2.json', 'bad_url2.json did not pass validation.
[url] Should use www.mediawiki.org domain
[url] Should use HTTPS for www.mediawiki.org URLs'
			]
		];
	}

}
