<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * @defgroup Pager Pager
 */

namespace MediaWiki\Pager;

use MediaWiki\Navigation\PagerNavigationBuilder;

/**
 * Basic pager interface for efficient paging through SQL queries.
 *
 * Must not be implemented directly by extensions,
 * instead extend IndexPager or one of its subclasses.
 *
 * @stable to type
 * @ingroup Pager
 */
interface Pager {
	/** @return string|PagerNavigationBuilder */
	public function getNavigationBar();

	/** @return string */
	public function getBody();
}

/** @deprecated class alias since 1.41 */
class_alias( Pager::class, 'Pager' );
