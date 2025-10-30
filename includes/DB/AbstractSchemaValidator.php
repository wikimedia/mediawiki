<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\DB;

use JsonSchema\Validator;
use Seld\JsonLint\DuplicateKeyException;
use Seld\JsonLint\JsonParser;
use Seld\JsonLint\ParsingException;
use function class_exists;
use function file_get_contents;
use function is_array;
use function is_object;

/**
 * Validate abstract schema json files against their JSON schema.
 *
 * This is used for static validation from the command-line via
 * generateSchemaSql.php, generateSchemaChangeSql, and the PHPUnit structure test suite
 * (AbstractSchemaTest).
 *
 * The files are normally read by the generateSchemaSql.php and generateSchemaSqlChange.php maintenance scripts.
 *
 * @since 1.38
 */
class AbstractSchemaValidator {
	/** @throws AbstractSchemaValidationError */
	public function __construct() {
		if ( !class_exists( Validator::class ) ) {
			throw new AbstractSchemaValidationError(
				'The JsonSchema library cannot be found, please install it through composer.'
			);
		}

		if ( !class_exists( JsonParser::class ) ) {
			throw new AbstractSchemaValidationError(
				'The JSON lint library cannot be found, please install it through composer.'
			);
		}
	}

	/**
	 * @param string $path file to validate
	 * @return bool true if passes validation
	 * @throws AbstractSchemaValidationError on any failure
	 */
	public function validate( string $path ): bool {
		$contents = file_get_contents( $path );
		$jsonParser = new JsonParser();
		try {
			$data = $jsonParser->parse( $contents, JsonParser::DETECT_KEY_CONFLICTS );
		} catch ( DuplicateKeyException $e ) {
			throw new AbstractSchemaValidationError( $e->getMessage(), $e->getCode(), $e );
		} catch ( ParsingException $e ) {
			throw new AbstractSchemaValidationError( "$path is not valid JSON", $e->getCode(), $e );
		}

		// Regular schema's are arrays, schema changes are objects.
		if ( is_array( $data ) ) {
			$schemaPath = __DIR__ . '/../../docs/abstract-schema.schema.json';
		} elseif ( is_object( $data ) ) {
			$schemaPath = __DIR__ . '/../../docs/abstract-schema-changes.schema.json';
		} else {
			throw new AbstractSchemaValidationError( "$path is not a supported JSON object" );
		}

		$validator = new Validator;
		$validator->check( $data, (object)[ '$ref' => 'file://' . $schemaPath ] );
		if ( $validator->isValid() ) {
			// All good.
			return true;
		}

		$out = "$path did not pass validation.\n";
		foreach ( $validator->getErrors() as $error ) {
			$out .= "[{$error['property']}] {$error['message']}\n";
		}
		throw new AbstractSchemaValidationError( $out );
	}
}
