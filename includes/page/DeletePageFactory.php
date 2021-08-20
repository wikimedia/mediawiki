<?php

namespace MediaWiki\Page;

use MediaWiki\Permissions\Authority;

/**
 * @since 1.37
 */
interface DeletePageFactory {

	/**
	 * @param ProperPageIdentity $page
	 * @param Authority $deleter
	 * @return DeletePage
	 */
	public function newDeletePage( ProperPageIdentity $page, Authority $deleter ): DeletePage;
}
