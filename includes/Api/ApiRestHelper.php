<?php

namespace MediaWiki\Api;

use MediaWiki\Rest\JsonFileLoaderTrait;
use RuntimeException;

/**
 * Helper for action API modules that support the REST paradigm.
 * This is used as glue by the ActionModuleBasedHandler in the REST framework
 * to help with translating an action API result to a REST response.
 *
 * @unstable since 1.47, will become stable to extend
 */
class ApiRestHelper {
	use JsonFileLoaderTrait;

	/**
	 * @param ApiRestHelperContext $context provides information about the REST
	 *        route definition. Typically passed through from ApiRestHybrid::initRestMode().
	 * @param ?string $responseBodySchemaFileName File path of a response body schema.
	 *        May be null if getResponseBodySchemaFileName() or getResponseBodySchema()
	 *        are overridden.
	 * @stable to call
	 */
	public function __construct(
		protected readonly ApiRestHelperContext $context,
		protected readonly ?string $responseBodySchemaFileName = null,
	) {
	}

	/**
	 * Returns the HTTP status code that should be returned to the client along
	 * with the given message. If 0 is returned, it is left to
	 * ActionModuleBasedHandler to determine the status code. This will generally
	 * result in status 400 being returned, except for some well known kinds
	 * of errors such as 'badtoken' (401) or 'ratelimited' (429).
	 *
	 * @stable to override
	 */
	public function getStatusForErrorMessage( IApiMessage $msg ): int {
		return 0;
	}

	/**
	 * Returns an OpenAPI Schema Object specification structure as an associative array.
	 *
	 * @note This method does not take a $method parameter like its pendant in
	 * Handler, because action API modules don't vary behavior on method.
	 * REST Handlers can (though probably shouldn't either).
	 *
	 * @see \MediaWiki\Rest\Handler::getResponseBodySchema() for details.
	 *
	 * @stable to override
	 * @return array
	 */
	public function getResponseBodySchema(): array {
		$fileName = $this->getResponseBodySchemaFileName();
		return self::loadJsonFile( $fileName );
	}

	/**
	 * Returns the absolute path of a JSON file containing an OpenAPI Schema
	 * Object specification structure describing the response body.
	 * @see \MediaWiki\Rest\Handler::getResponseBodySchema() for details.
	 *
	 * @stable to override
	 * @return string
	 */
	public function getResponseBodySchemaFileName(): string {
		if ( $this->responseBodySchemaFileName === null ) {
			throw new RuntimeException(
				'No $responseBodySchemaFileName passed to the constructor, please override' .
				' getResponseBodySchemaFileName() or getResponseBodySchema()'
			);
		}
		return $this->responseBodySchemaFileName;
	}

}
