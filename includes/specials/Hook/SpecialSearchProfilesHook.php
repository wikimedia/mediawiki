<?php

namespace MediaWiki\Hook;

/**
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
