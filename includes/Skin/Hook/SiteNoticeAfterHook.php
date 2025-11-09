<?php

namespace MediaWiki\Skin\Hook;

use MediaWiki\Skin\Skin;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SiteNoticeAfter" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SiteNoticeAfterHook {
	/**
	 * This hook is called after the sitenotice/anonnotice is composed.
	 *
	 * @since 1.35
	 *
	 * @param string &$siteNotice HTML sitenotice. Alter the contents of $siteNotice to add to/alter
	 *   the sitenotice/anonnotice.
	 * @param Skin $skin
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSiteNoticeAfter( &$siteNotice, $skin );
}

/** @deprecated class alias since 1.46 */
class_alias( SiteNoticeAfterHook::class, 'MediaWiki\\Hook\\SiteNoticeAfterHook' );
