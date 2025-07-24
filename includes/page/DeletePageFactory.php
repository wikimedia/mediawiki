<?php

namespace MediaWiki\Page;

use MediaWiki\Permissions\Authority;

/**
 * Service for page delete actions.
 *
 * Default implementation is MediaWiki\Page\PageCommandFactory.
 *
 * @since 1.37
 * @ingroup Page
 */
interface DeletePageFactory {

	/**
	 * @param ProperPageIdentity $page
	 * @param Authority $deleter
	 * @return DeletePage
	 */
	public function newDeletePage( ProperPageIdentity $page, Authority $deleter ): DeletePage;
}
