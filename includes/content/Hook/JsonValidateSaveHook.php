<?php

namespace MediaWiki\Content\Hook;

use JsonContent;
use MediaWiki\Page\PageIdentity;
use StatusValue;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "JsonValidateSaveHook" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface JsonValidateSaveHook {
	/**
	 * Use this hook to add additional validations for JSON content pages.
	 * This hook is only called if JSON syntax validity and other contentmodel-specific validations
	 * are passing.
	 *
	 * @since 1.39
	 *
	 * @param JsonContent $content
	 * @param PageIdentity $pageIdentity
	 * @param StatusValue $status Fatal errors only would trigger validation failure as $status is checked with isOK()
	 * @return bool|void True or no return value to continue
	 */
	public function onJsonValidateSave( JsonContent $content, PageIdentity $pageIdentity, StatusValue $status );
}
