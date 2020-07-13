<?php

namespace MediaWiki\Hook;

use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface TitleGetEditNoticesHook {
	/**
	 * Use this hook to add edit notices.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title object for the page the edit notices are for
	 * @param int $oldid Revision ID that the edit notices are for (or 0 for latest)
	 * @param array &$notices Array of notices. Keys are i18n message keys, values are
	 *   parseAsBlock()ed messages.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleGetEditNotices( $title, $oldid, &$notices );
}
