<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialSearchProfilesHook {
	/**
	 * Allows modification of search profiles.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$profiles profiles, which can be modified.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialSearchProfiles( &$profiles );
}
