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

/**
 * Validates all loaded extensions and skins using the ExtensionRegistry
 * against the extension.json schema in the docs/ folder.
 *
 * @coversNothing
 */
class ExtensionJsonValidationTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @var ExtensionJsonValidator
	 */
	protected $validator;

	public function setUp() {
		parent::setUp();

		$this->validator = new ExtensionJsonValidator( [ $this, 'markTestSkipped' ] );
		$this->validator->checkDependencies();

		if ( !ExtensionRegistry::getInstance()->getAllThings() ) {
			$this->markTestSkipped(
				'There are no extensions or skins loaded via the ExtensionRegistry'
			);
		}
	}

	public static function providePassesValidation() {
		$values = [];
		foreach ( ExtensionRegistry::getInstance()->getAllThings() as $thing ) {
			$values[] = [ $thing['path'] ];
		}

		return $values;
	}

	/**
	 * @dataProvider providePassesValidation
	 * @param string $path Path to thing's json file
	 */
	public function testPassesValidation( $path ) {
		try {
			$this->validator->validate( $path );
			// All good
			$this->assertTrue( true );
		} catch ( ExtensionJsonValidationError $e ) {
			$this->assertEquals( false, $e->getMessage() );
		}
	}
}
