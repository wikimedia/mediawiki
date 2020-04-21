<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialRandomGetRandomTitleHook {
	/**
	 * Called during the execution of Special:Random,
	 * use this to change some selection criteria or substitute a different title.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$randstr The random number from wfRandom()
	 * @param ?mixed &$isRedir Boolean, whether to select a redirect or non-redirect
	 * @param ?mixed &$namespaces An array of namespace indexes to get the title from
	 * @param ?mixed &$extra An array of extra SQL statements
	 * @param ?mixed &$title If the hook returns false, a Title object to use instead of the
	 *   result from the normal query
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialRandomGetRandomTitle( &$randstr, &$isRedir,
		&$namespaces, &$extra, &$title
	);
}
