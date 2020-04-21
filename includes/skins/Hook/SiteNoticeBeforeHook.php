<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SiteNoticeBeforeHook {
	/**
	 * Before the sitenotice/anonnotice is composed. Return true to
	 * allow the normal method of notice selection/rendering to work, or change the
	 * value of $siteNotice and return false to alter it.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$siteNotice HTML returned as the sitenotice
	 * @param ?mixed $skin Skin object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSiteNoticeBefore( &$siteNotice, $skin );
}
