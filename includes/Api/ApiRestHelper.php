<?php

namespace MediaWiki\Api;

use RuntimeException;

/**
 * Helper for action API modules that support the REST paradigm.
 * This is used as glue by the ActionModuleBasedHandler in the REST framework
 * to help with translating an action API result to a REST response.
 *
 * @unstable since 1.47
 */
abstract class ApiRestHelper {

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

		// NOTE: Below is the same code as in Module::loadJsonFile, but we
		//      don't want to depend on that here.
		$json = file_get_contents( $fileName );
		if ( $json === false ) {
			// XXX: What kind of exception should we throw? The equivalent code in
			// Handler uses ModuleConfigurationException, but we shouldn't depend
			// on code from the REST framework here.
			throw new RuntimeException( "Failed to load file `$fileName`" );
		}

		$spec = json_decode( $json, true );

		if ( !is_array( $spec ) ) {
			throw new RuntimeException(
				"Failed to parse `$fileName` as a JSON object"
			);
		}

		return $spec;
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
