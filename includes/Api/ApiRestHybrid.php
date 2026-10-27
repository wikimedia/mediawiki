<?php

namespace MediaWiki\Api;

/**
 * Interface for action API modules that also support the REST paradigm.
 * This is used as glue by the ActionModuleBasedHandler in the REST framework
 * to help with translating an action API result to a REST response.
 *
 * Since interfaces can't evolve, the interface has a single method that returns
 * a helper object. The helper object is defined using a base class which can
 * evolve.
 *
 * @unstable since 1.47, will become stable to implement
 */
interface ApiRestHybrid {

	/**
	 * Initiates REST mode and returns the helper object that will act as glue.
	 * Api modules that implement this method may use it to trigger a "REST mode",
	 * e.g. to change what parameters are allowed.
	 *
	 * The ApiRestHelper must provide a response body schema.
	 *
	 * @param ApiRestHelperContext $context
	 *
	 * @return ApiRestHelper
	 */
	public function initRestMode( ApiRestHelperContext $context ): ApiRestHelper;

}
