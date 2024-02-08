<?php

namespace MediaWiki\Actions\Hook;

use MediaWiki\Context\IContextSource;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GetActionNameHook {
	/**
	 * Use this hook to override the action name depending on request parameters.
	 *
	 * @since 1.37
	 *
	 * @param IContextSource $context Request context
	 * @param string &$action Default action name, reassign to change it
	 * @return void This hook must not abort, it must return no value
	 */
	public function onGetActionName( IContextSource $context, string &$action ): void;
}
