<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialContributionsBeforeMainOutputHook {
	/**
	 * Before the form on Special:Contributions
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $id User id number, only provided for backwards-compatibility
	 * @param ?mixed $user User object representing user contributions are being fetched for
	 * @param ?mixed $sp SpecialPage instance, providing context
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialContributionsBeforeMainOutput( $id, $user, $sp );
}
