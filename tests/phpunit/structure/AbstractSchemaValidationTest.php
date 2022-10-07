<?php
/**
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
 */

use MediaWiki\DB\AbstractSchemaValidationError;
use MediaWiki\DB\AbstractSchemaValidator;

/**
 * Validates all abstract schemas against the abstract-schema schemas in the docs/ folder.
 * @coversNothing
 */
class AbstractSchemaValidationTest extends PHPUnit\Framework\TestCase {
	use MediaWikiCoversValidator;

	/**
	 * @var AbstractSchemaValidator
	 */
	protected $validator;

	protected function setUp(): void {
		parent::setUp();

		$this->validator = new AbstractSchemaValidator( [ $this, 'markTestSkipped' ] );
		$this->validator->checkDependencies();
	}

	public static function provideSchemas(): array {
		return [
			'maintenance/tables.json' => [ __DIR__ . '/../../../maintenance/tables.json' ]
		];
	}

	/**
	 * @dataProvider provideSchemas
	 * @param string $path Path to tables.json file
	 */
	public function testSchemasPassValidation( string $path ): void {
		try {
			$this->validator->validate( $path );
			// All good
			$this->assertTrue( true );
		} catch ( AbstractSchemaValidationError $e ) {
			$this->fail( $e->getMessage() );
		}
	}

	public static function provideSchemaChanges(): Generator {
		foreach ( glob( __DIR__ . '/../../../maintenance/abstractSchemaChanges/*.json' ) as $schemaChange ) {
			$fileName = pathinfo( $schemaChange, PATHINFO_BASENAME );

			yield $fileName => [ $schemaChange ];
		}
	}

	/**
	 * @dataProvider provideSchemaChanges
	 * @param string $path Path to tables.json file
	 */
	public function testSchemaChangesPassValidation( string $path ): void {
		try {
			$this->validator->validate( $path );
			// All good
			$this->assertTrue( true );
		} catch ( AbstractSchemaValidationError $e ) {
			$this->fail( $e->getMessage() );
		}
	}
}
