<?php

namespace MediaWiki\Hook;

use MediaWiki\SpecialPage\ContributionsSpecialPage;
use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialContributionsBeforeMainOutput" to register handlers implementing this interface.
 *
 * This hook is run for any ContributionsSpecialPage, not just SpecialContributions.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialContributionsBeforeMainOutputHook {
	/**
	 * This hook is called before the form on Special:Contributions
	 *
	 * @since 1.35
	 *
	 * @param int $id User id number, only provided for backwards-compatibility
	 * @param User $user User object representing user contributions are being fetched for
	 * @param ContributionsSpecialPage $sp SpecialPage instance, providing context
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialContributionsBeforeMainOutput( $id, $user, $sp );
}
