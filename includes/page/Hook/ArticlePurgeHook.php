<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticlePurgeHook {
	/**
	 * Before executing "&action=purge".
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $wikiPage WikiPage (object) to purge
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticlePurge( $wikiPage );
}
