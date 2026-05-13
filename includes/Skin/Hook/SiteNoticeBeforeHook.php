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
	 * Replace or disable the sitenotice.
	 *
	 * The default sitenotice considers the sitenotice, anonnotice, namespacenotice, and confirmemail-notice messages,
	 * as well as $wgSiteNotice feature.
	 *
	 * To replace the sitenotice, set the $siteNotice arg to an HTML string and return false.
	 *
	 * To disable the sitenotice, set the $siteNotice arg to the empty string and return false.
	 *
	 * @since 1.35
	 * @param string &$siteNotice HTML returned as the sitenotice
	 * @param Skin $skin
	 * @return bool|void True or no return value to continue as normal, or false to prevent
	 * applying the default behavior.
	 */
	public function onSiteNoticeBefore( &$siteNotice, $skin );
}

/** @deprecated class alias since 1.46 */
class_alias( SiteNoticeBeforeHook::class, 'MediaWiki\\Hook\\SiteNoticeBeforeHook' );
