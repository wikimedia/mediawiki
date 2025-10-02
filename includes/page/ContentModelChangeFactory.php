<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author DannyS712
 */

namespace MediaWiki\Page;

use MediaWiki\Content\ContentModelChange;
use MediaWiki\Permissions\Authority;

/**
 * Service for changing the content model of wiki pages.
 *
 * Default implementation is MediaWiki\Page\PageCommandFactory.
 *
 * @since 1.35
 */
interface ContentModelChangeFactory {

	/**
	 * @param Authority $performer
	 * @param PageIdentity $page
	 * @param string $newContentModel
	 * @return ContentModelChange
	 */
	public function newContentModelChange(
		Authority $performer,
		PageIdentity $page,
		string $newContentModel
	): ContentModelChange;
}
