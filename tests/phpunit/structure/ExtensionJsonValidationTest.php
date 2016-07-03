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
 */
class ExtensionJsonValidationTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		parent::setUp();
		if ( !class_exists( 'JsonSchema\Uri\UriRetriever' ) ) {
			$this->markTestSkipped(
				'The JsonSchema library cannot be found,' .
				' please install it through composer to run extension.json validation tests.'
			);
		}

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
		$data = json_decode( file_get_contents( $path ) );
		$this->assertInstanceOf( 'stdClass', $data, "$path is not valid JSON" );

		$this->assertObjectHasAttribute( 'manifest_version', $data,
			"$path does not have manifest_version set." );
		$version = $data->manifest_version;
		if ( $version !== ExtensionRegistry::MANIFEST_VERSION ) {
			$schemaPath = __DIR__ . "/../../../docs/extension.schema.v$version.json";
		} else {
			$schemaPath = __DIR__ . '/../../../docs/extension.schema.json';
		}

		// Not too old
		$this->assertTrue(
			$version >= ExtensionRegistry::OLDEST_MANIFEST_VERSION,
			"$path is using a non-supported schema version"
		);
		// Not too new
		$this->assertTrue(
			$version <= ExtensionRegistry::MANIFEST_VERSION,
			"$path is using a non-supported schema version"
		);
		$retriever = new JsonSchema\Uri\UriRetriever();
		$schema = $retriever->retrieve( 'file://' . $schemaPath );

		$validator = new JsonSchema\Validator();
		$validator->check( $data, $schema );
		if ( $validator->isValid() ) {
			// All good.
			$this->assertTrue( true );
		} else {
			$out = "$path did pass validation.\n";
			foreach ( $validator->getErrors() as $error ) {
				$out .= "[{$error['property']}] {$error['message']}\n";
			}
			$this->assertTrue( false, $out );
		}
	}
}
