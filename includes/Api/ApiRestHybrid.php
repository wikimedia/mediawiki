<?php

namespace MediaWiki\Api;

/**
 * Interface for action API modules that also support the REST paradigm.
 * This is used as glue by the ActionModuleBasedHandler in the REST framework
 * to help with translating an action API result to a REST response.
 *
 * Since interfaces can't evolve, the interface has a single method that returns
 * a helper object. The helper object is defined using an abstract base class
 * which can evolve.
 *
 * @stable to implement
 * @since 1.47
 */
interface ApiRestHybrid {

	public function getRestHelper(): ApiRestHelper;

}
