<?php
/**
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

use MediaWiki\DB\AbstractSchemaValidationError;
use MediaWiki\DB\AbstractSchemaValidator;

/**
 * @covers \MediaWiki\DB\AbstractSchemaValidator
 */
class AbstractSchemaValidatorTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideValidate
	 * @param string $file
	 * @param string|true $expected
	 */
	public function testValidate( string $file, $expected ): void {
		// If a dependency is missing, skip this test.
		$validator = new AbstractSchemaValidator( function ( $msg ) {
			$this->markTestSkipped( $msg );
		} );

		if ( is_string( $expected ) ) {
			$this->expectException( AbstractSchemaValidationError::class );
			$this->expectExceptionMessage( $expected );
		}

		$dir = __DIR__ . '/../../../data/db/';
		$this->assertSame(
			$expected,
			$validator->validate( $dir . $file )
		);
	}

	public static function provideValidate(): array {
		return [
			[
				'tables.json',
				true
			],
			[
				'patch-drop-ct_tag.json',
				true
			],
			[
				'notschema.txt',
				'notschema.txt is not valid JSON'
			]
		];
	}
}
