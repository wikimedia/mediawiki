<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MediaWikiServicesHook {
	/**
	 * Called when a global MediaWikiServices instance is
	 * initialized. Extensions may use this to define, replace, or wrap services.
	 * However, the preferred way to define a new service is
	 * the $wgServiceWiringFiles array.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $services MediaWikiServices
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMediaWikiServices( $services );
}
