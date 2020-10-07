<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use SkinTemplate;

/**
 * @deprecated since 1.35. Use SidebarBeforeOutput hook
 * @ingroup Hooks
 */
interface SkinTemplateBuildNavUrlsNav_urlsAfterPermalinkHook {
	/**
	 * This hook is called after creating the "permanent link" tab.
	 *
	 * @since 1.35
	 *
	 * @param SkinTemplate $sktemplate
	 * @param array &$nav_urls Array of tabs
	 * @param int &$revid Revision ID of the permanent link
	 * @param int &$revid2 Revision ID of the permanent link, second time
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplateBuildNavUrlsNav_urlsAfterPermalink( $sktemplate,
		&$nav_urls, &$revid, &$revid2
	);
}
