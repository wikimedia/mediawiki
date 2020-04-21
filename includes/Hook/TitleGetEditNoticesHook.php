<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleGetEditNoticesHook {
	/**
	 * Allows extensions to add edit notices
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title The Title object for the page the edit notices are for
	 * @param ?mixed $oldid Revision ID that the edit notices are for (or 0 for latest)
	 * @param ?mixed &$notices Array of notices. Keys are i18n message keys, values are
	 *   parseAsBlock()ed messages.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleGetEditNotices( $title, $oldid, &$notices );
}
