<?php

namespace MediaWiki\Page;

use MediaWiki\Permissions\Authority;

/**
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
