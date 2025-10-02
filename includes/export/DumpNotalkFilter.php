<?php
/**
 * Simple dump output filter to exclude all talk pages.
 *
 * Copyright © 2003, 2005, 2006 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\MediaWikiServices;

/**
 * @ingroup Dump
 */
class DumpNotalkFilter extends DumpFilter {
	/**
	 * @param stdClass $page
	 * @return bool
	 */
	protected function pass( $page ) {
		return !MediaWikiServices::getInstance()->getNamespaceInfo()->
			isTalk( $page->page_namespace );
	}
}
