<?php

namespace MediaWiki\Skin\Hook;

use MediaWiki\Skin\Skin;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SiteNoticeBefore" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SiteNoticeBeforeHook {
	/**
	 * This hook is called before the sitenotice/anonnotice is composed.
	 *
	 * @since 1.35
	 *
	 * @param string &$siteNotice HTML returned as the sitenotice
	 * @param Skin $skin
	 * @return bool|void True or no return value to continue or false to abort.
	 *   Return true to allow the normal method of notice selection/rendering to work,
	 *   or change the value of $siteNotice and return false to alter it.
	 */
	public function onSiteNoticeBefore( &$siteNotice, $skin );
}

/** @deprecated class alias since 1.46 */
class_alias( SiteNoticeBeforeHook::class, 'MediaWiki\\Hook\\SiteNoticeBeforeHook' );
