<?php

namespace MediaWiki\Page;

use MediaWiki\Title\Title;

/**
 * Convenience trait for conversion to PageIdentity.
 *
 * For the cross-wiki aware code, this should be used instead of PageIdentity::getId
 * until Title is dropped. Before transition to PageIdentity, Title could exist for
 * foreign wikis with no indication about it (Title does not have $wikiId).
 * It was very brittle, but it worked. Until Title is deprecated in the codebase,
 * most of the PageIdentity instances passed around are Titles. So for cross-wiki access,
 * stricter domain validation of PageIdentity::getId will break wikis.
 *
 * Additionally, loose checks on Title regarding whether the page can exist or not
 * have been depended upon in a number of places in the codebase.
 *
 * This trait is only supposed to be used in cross-wiki aware code, and only exists until
 * code up the stack is guaranteed not to pass Title.
 *
 * @internal
 */
trait LegacyArticleIdAccess {
	/**
	 * Before transition to PageIdentity, Title could exist for foreign wikis.
	 * It was very brittle, but it worked. Until Title is deprecated in the codebase,
	 * most of the PageIdentity instances passed around are Titles.
	 * So for cross-wiki access, stricter domain validation of PageIdentity::getId
	 * will break wikis. This method supposed to exist only for the transition period
	 * and will be removed after.
	 *
	 * Additionally, loose checks on Title regarding whether the page can exist or not
	 * have been depended upon in a number of places in the codebase.
	 *
	 * @param PageIdentity $title
	 * @return int
	 */
	protected function getArticleId( PageIdentity $title ): int {
		if ( $title instanceof Title ) {
			return $title->getArticleID();
		}
		return $title->getId( $this->getWikiId() );
	}

	/**
	 * Get the ID of the wiki this revision belongs to.
	 *
	 * @return string|false The wiki's logical name, of false to indicate the local wiki.
	 */
	abstract protected function getWikiId();
}
