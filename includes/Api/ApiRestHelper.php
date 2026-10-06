<?php

namespace MediaWiki\Api;

use MediaWiki\Rest\JsonFileLoaderTrait;

/**
 * Helper for action API modules that support the REST paradigm.
 * This is used as glue by the ActionModuleBasedHandler in the REST framework
 * to help with translating an action API result to a REST response.
 *
 * @unstable since 1.47
 */
abstract class ApiRestHelper {
	use JsonFileLoaderTrait;

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
		$fileName = $this->getResponseBodySchemaFileName();

		if ( $fileName === null ) {
			return null;
		}

		return self::loadJsonFile( $fileName );
	}

	/**
	 * Returns the absolute path of a JSON file containing an OpenAPI Schema
	 * Object specification structure describing the response body.
	 *
	 * @see \MediaWiki\Rest\Handler::getResponseBodySchema() for details.
	 * @return ?string
	 */
	public function getResponseBodySchemaFileName(): ?string {
		return null;
	}

}
