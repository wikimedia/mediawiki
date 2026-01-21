<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@debian.org>
 *
 * @license GPL-2.0-or-later
 */
namespace MediaWiki\Tests\Unit\Registration;

use MediaWiki\Registration\ExtensionJsonValidationError;
use MediaWiki\Registration\ExtensionJsonValidator;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Registration\ExtensionJsonValidator
 */
class ExtensionJsonValidatorTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideValidate
	 */
	public function testValidate( $file, $expected ) {
		// If a dependency is missing, skip this test.
		$validator = new ExtensionJsonValidator( $this->markTestSkipped( ... ) );

		if ( is_string( $expected ) ) {
			$this->expectException( ExtensionJsonValidationError::class );
			$this->expectExceptionMessage( $expected );
		}

		$dir = __DIR__ . '/../../../data/registration/';
		$this->assertSame(
			$expected,
			$validator->validate( $dir . $file )
		);
	}

	public static function provideValidate() {
		return [
			[
				'example-basic.json',
				true
			],
			[
				'example-edgecases1.json',
				true
			],
			[
				'example-edgecases2.json',
				true
			],
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
				'good_with_license_expressions.json',
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
