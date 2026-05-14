<?php

namespace MediaWiki\ShadowPage;

use MediaWiki\Page\PageReference;
use Wikimedia\Parsoid\Core\LinkTarget;

/**
 * The interface for providers of ShadowPage instances.
 *
 * Extensions should extend BaseShadowPageProvider, to avoid a b/c break if
 * we decide to add more methods here.
 *
 * @since 1.47
 */
interface ShadowPageProvider {
	/**
	 * If the implementation wishes to handle this title, return a
	 * ShadowPage. Return null to allow the loader to try the next
	 * provider.
	 *
	 * @param PageReference $title
	 * @return ShadowPage|null
	 */
	public function get( PageReference $title ): ?ShadowPage;

	/**
	 * Determine whether a link should be coloured as if it refers to an
	 * existing page.
	 *
	 * @param LinkTarget $link
	 * @return bool
	 */
	public function existsForLink( LinkTarget $link ): bool;
}
