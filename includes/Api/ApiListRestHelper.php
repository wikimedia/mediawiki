<?php

namespace MediaWiki\Api;

/**
 * Helper for action API list modules that support the REST paradigm.
 * This is used as glue by the ActionModuleBasedHandler in the REST framework
 * to help with translating an action API result to a REST response.
 *
 * @unstable since 1.47
 */
abstract class ApiListRestHelper extends ApiRestHelper {

	/**
	 * Returns an OpenAPI Schema Object specification structure as an associative array.
	 * @see \MediaWiki\Rest\Handler::getResponseBodySchema() for details.
	 *
	 * @note This method does not take a $method parameter like its pendant in
	 * Handler, because action API modules don't vary behavior on method.
	 * REST Handlers can (though probably shouldn't either).
	 *
	 * @stable to override
	 * @return ?array
	 */
	public function getResponseBodySchema(): ?array {
		$spec = parent::getResponseBodySchema();

		$fileName = $this->getResponseItemSchemaFileName();
		if ( $fileName !== null ) {
			$itemSpec = self::loadJsonFile( $fileName );

			// Inject the item spec into the response spec in the appropriate location.
			// The slot is an empty schema, which is falsy, so test for the key instead.
			foreach ( $spec['allOf'] ?? [] as $index => $subSpec ) {
				if ( isset( $subSpec['properties']['items'] )
					&& array_key_exists( 'items', $subSpec['properties']['items'] )
				) {
					$spec['allOf'][$index]['properties']['items']['items'] = $itemSpec;
				}
			}
		}

		return $spec;
	}

	/**
	 * Returns the absolute path of a JSON file containing an OpenAPI Schema
	 * Object specification structure describing the items of the returned
	 * by the module.
	 *
	 * @see \MediaWiki\Rest\Handler::getResponseBodySchema() for details.
	 * @return ?string
	 */
	public function getResponseItemSchemaFileName(): ?string {
		return null;
	}

	/**
	 * Returns the absolute path of a JSON file containing an OpenAPI Schema
	 * Object specification structure describing the response body
	 * envelope for list responses.
	 *
	 * The schema loaded from getResponseItemSchemaFileName() will be injected
	 * into this schema.
	 *
	 * @see \MediaWiki\Rest\Handler::getResponseBodySchema() for details.
	 * @return ?string
	 */
	public function getResponseBodySchemaFileName(): ?string {
		return __DIR__ . '/Schema/List-response.json';
	}

}
