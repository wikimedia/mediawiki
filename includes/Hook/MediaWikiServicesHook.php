<?php

namespace MediaWiki\Hook;

use MediaWiki\MediaWikiServices;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MediaWikiServices" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MediaWikiServicesHook {
	/**
	 * This hook is called when a global MediaWikiServices instance is initialized.
	 * Extensions may use this to define, replace, or wrap services. However, the
	 * preferred way to define a new service is the $wgServiceWiringFiles array.
	 *
	 * @since 1.35
	 *
	 * @param MediaWikiServices $services
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMediaWikiServices( $services );
}
