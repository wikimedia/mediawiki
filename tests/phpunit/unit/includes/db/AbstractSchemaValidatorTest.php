<?php
/**
 * @license GPL-2.0-or-later
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
		$validator = new AbstractSchemaValidator();

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
