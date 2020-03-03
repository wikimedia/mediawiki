<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PersonalUrlsHook {
	/**
	 * Alter the user-specific navigation links (e.g. "my page,
	 * my talk page, my contributions" etc).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$personal_urls Array of link specifiers (see SkinTemplate.php)
	 * @param ?mixed &$title Title object representing the current page
	 * @param ?mixed $skin SkinTemplate object providing context (e.g. to check if the user is
	 *   logged in, etc.)
	 * @return bool|void This hook must not abort, it must return true or null.
	 */
	public function onPersonalUrls( &$personal_urls, &$title, $skin );
}
