<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetDoubleUnderscoreIDsHook {
	/**
	 * Modify the list of behavior switch (double
	 * underscore) magic words. Called by MagicWord.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$doubleUnderscoreIDs array of strings
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetDoubleUnderscoreIDs( &$doubleUnderscoreIDs );
}
