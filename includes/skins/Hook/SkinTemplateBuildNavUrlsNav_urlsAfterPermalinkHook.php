<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SkinTemplateBuildNavUrlsNav_urlsAfterPermalinkHook {
	/**
	 * After creating the "permanent
	 * link" tab.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $sktemplate SkinTemplate object
	 * @param ?mixed &$nav_urls array of tabs
	 * @param ?mixed &$revid The revision id of the permanent link
	 * @param ?mixed &$revid2 The revision id of the permanent link, second time
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSkinTemplateBuildNavUrlsNav_urlsAfterPermalink( $sktemplate,
		&$nav_urls, &$revid, &$revid2
	);
}
