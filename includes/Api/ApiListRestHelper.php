<?php

namespace MediaWiki\Api;

use RuntimeException;

/**
 * Helper for action API list modules that support the REST paradigm.
 * This is used as glue by the ActionModuleBasedHandler in the REST framework
 * to help with translating an action API result to a REST response.
 *
 * @unstable since 1.47, will become stable to extend
 */
class ApiListRestHelper extends ApiRestHelper {
	public function __construct(
		ApiRestHelperContext $context,
		protected readonly ?string $responseItemSchemaFileName = null
	) {
		parent::__construct(
			$context,
			__DIR__ . '/Schema/List-response.json'
		);
	}

	/**
	 * Returns an OpenAPI Schema Object specification structure as an associative array.
	 * @see \MediaWiki\Rest\Handler::getResponseBodySchema() for details.
	 *
	 * @note This method does not take a $method parameter like its pendant in
	 * Handler, because action API modules don't vary behavior on method.
	 * REST Handlers can (though probably shouldn't either).
	 *
	 * @stable to override
	 * @return array
	 */
	public function getResponseBodySchema(): array {
		$spec = parent::getResponseBodySchema();
		$itemSpec = $this->getResponseItemSchema();

		// Inject the item spec into the response spec in the appropriate location.
		// The slot is an empty schema, which is falsy, so test for the key instead.
		foreach ( $spec['allOf'] ?? [] as $index => $subSpec ) {
			if ( isset( $subSpec['properties']['items'] )
				&& array_key_exists( 'items', $subSpec['properties']['items'] )
			) {
				$spec['allOf'][$index]['properties']['items']['items'] = $itemSpec;
			}
		}

		return $spec;
	}

	/**
	 * Returns an OpenAPI Schema Object specification structure describing
	 * a single item returned by the module, as an associative array.
	 *
	 * @stable to override
	 * @return array
	 */
	public function getResponseItemSchema(): array {
		$fileName = $this->getResponseItemSchemaFileName();
		return self::loadJsonFile( $fileName );
	}

	/**
	 * Returns the absolute path of a JSON file containing an OpenAPI Schema
	 * Object specification structure describing the items returned by the
	 * module.
	 *
	 * @see \MediaWiki\Rest\Handler::getResponseBodySchema() for details.
	 *
	 * @stable to override
	 * @return string
	 */
	public function getResponseItemSchemaFileName(): string {
		if ( $this->responseItemSchemaFileName === null ) {
			throw new RuntimeException(
				'No $responseItemSchemaFileName passed to the constructor, please override' .
				' getResponseItemSchemaFileName() or getResponseItemSchema()'
			);
		}
		return $this->responseItemSchemaFileName;
	}

}
