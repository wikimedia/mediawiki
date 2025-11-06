<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialSearchProfiles" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialSearchProfilesHook {
	/**
	 * Use this hook to modify search profiles.
	 *
	 * @since 1.35
	 *
	 * @param array &$profiles profiles, which can be modified.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchProfiles( &$profiles );
}
