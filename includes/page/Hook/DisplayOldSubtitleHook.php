<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DisplayOldSubtitleHook {
	/**
	 * before creating subtitle when browsing old versions of
	 * an article
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $article article (object) being viewed
	 * @param ?mixed &$oldid oldid (int) being viewed
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDisplayOldSubtitle( $article, &$oldid );
}
