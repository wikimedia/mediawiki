<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiRsdServiceApisHook {
	/**
	 * Use this hook to add or remove APIs from the RSD services list. Each service
	 * should have its own entry in the $apis array and have a unique name, passed as
	 * key for the array that represents the service data. In this data array, the
	 * key-value-pair identified by the apiLink key is required.
	 *
	 * @since 1.35
	 *
	 * @param array &$apis Array of services
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiRsdServiceApis( &$apis );
}
