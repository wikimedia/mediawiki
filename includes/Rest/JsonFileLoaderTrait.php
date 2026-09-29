<?php

namespace MediaWiki\Rest;

use MediaWiki\Rest\PathTemplateMatcher\ModuleConfigurationException;

/**
 * Loads the JSON files that ship with the REST framework, such as module
 * definitions and OpenAPI schemas.
 *
 * @internal
 */
trait JsonFileLoaderTrait {

	/**
	 * Loads a JSON file.
	 *
	 * This method does not know or care about the structure of the file
	 * other than that it must be JSON and contain a list or map
	 * (that is, a JSON array or object).
	 *
	 * @param string $fileName
	 *
	 * @return array An associative or indexed array
	 * @throws ModuleConfigurationException
	 */
	public static function loadJsonFile( string $fileName ): array {
		$json = file_get_contents( $fileName );
		if ( $json === false ) {
			throw new ModuleConfigurationException(
				"Failed to load file `$fileName`"
			);
		}

		$spec = json_decode( $json, true );

		if ( !is_array( $spec ) ) {
			throw new ModuleConfigurationException(
				"Failed to parse `$fileName` as a JSON object"
			);
		}

		return $spec;
	}
}
