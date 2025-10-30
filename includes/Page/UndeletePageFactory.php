<?php

namespace MediaWiki\Page;

use MediaWiki\Permissions\Authority;

/**
 * Service for page undelete actions.
 *
 * Default implementation is MediaWiki\Page\PageCommandFactory.
 *
 * @since 1.38
 */
interface UndeletePageFactory {

	/**
	 * @param ProperPageIdentity $page
	 * @param Authority $authority
	 * @return UndeletePage
	 */
	public function newUndeletePage( ProperPageIdentity $page, Authority $authority ): UndeletePage;
}
