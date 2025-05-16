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

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\Validator;

/**
 * Trait for validating data structures against JSON schemas.
 *
 * Based on the jsonrainbow/json-schema package, supports Draft-3 and Draft-4
 * schemas.
 *
 * @stable to use in extensions
 * @since 1.43
 */
trait JsonSchemaAssertionTrait {

	/**
	 * Load data from a JSON file.
	 *
	 * @param string $jsonFile The path of the JSON file
	 *
	 * @return mixed
	 */
	private static function loadJsonData( string $jsonFile ) {
		$json = file_get_contents( $jsonFile );

		if ( $json === false ) {
			throw new InvalidArgumentException( "Unable to load content of $jsonFile" );
		}

		return json_decode( $json, false, JSON_THROW_ON_ERROR );
	}

	/**
	 * @param string|array $schema A JSON schema as an array structure, or the
	 *        file path of a JSON schema file.
	 * @param string|array|stdClass $json A JSONic data structure, or a JSON string.
	 * @param array<string, string> $resources Mapping of schema IDs to local files,
	 *        to avoid loading schemas over the network during testing.
	 * @param string $message
	 */
	private function assertMatchesJsonSchema( $schema, $json, array $resources = [], string $message = '' ): void {
		if ( is_string( $json ) ) {
			try {
				$json = json_decode( $json, false, JSON_THROW_ON_ERROR );
			} catch ( JsonException $ex ) {
				self::fail( ( $message !== '' ? $message . ' - ' : '' ) . 'Invalid JSON: ' . $ex->getMessage() );
			}
		}
		if ( is_string( $schema ) ) {
			// Let the JsonException propagate, this indicates a bug in the test,
			// not a test failure.
			$schema = self::loadJsonData( $schema );
		}

		$factory = new Factory();

		foreach ( $resources as $id => $rc ) {
			$factory->getSchemaStorage()->addSchema(
				rtrim( $id, '#' ),
				self::loadJsonData( $rc )
			);
		}

		$validator = new Validator( $factory );
		$validator->validate(
			$json, $schema,
			Constraint::CHECK_MODE_TYPE_CAST
		);

		if ( !$validator->isValid() ) {
			foreach ( $validator->getErrors() as $error ) {
				$error = json_encode( $error );
				self::fail( ( $message !== '' ? $message . ' - ' : '' ) . "JSON schema validation failed: $error" );
			}
		}

		$this->addToAssertionCount( 1 );
	}

	/**
	 * Validate a JSON schema.
	 *
	 * Currently only works for schemas that match Draft-4.
	 *
	 * @param array $schema The schema to validate.
	 * @param string $message
	 * @throws LogicException if the schema is invalid
	 */
	public function assertValidJsonSchema( array $schema, string $message = '' ): void {
		// Load the draft-04 schema from the local file
		$metaSchema = MW_INSTALL_PATH . '/vendor/justinrainbow/json-schema/dist/' .
			'schema/json-schema-draft-04.json';

		self::assertMatchesJsonSchema( $metaSchema, $schema, [], $message );
	}

}
