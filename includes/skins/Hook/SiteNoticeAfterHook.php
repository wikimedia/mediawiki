<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SiteNoticeAfterHook {
	/**
	 * After the sitenotice/anonnotice is composed.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$siteNotice HTML sitenotice. Alter the contents of $siteNotice to add to/alter
	 *   the sitenotice/anonnotice.
	 * @param ?mixed $skin Skin object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSiteNoticeAfter( &$siteNotice, $skin );
}
