<?php

namespace MediaWiki\Hook;

use OutputPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MakeGlobalVariablesScriptHook {
	/**
	 * This hook is called at end of OutputPage::getJSVars.
	 * Ideally, this hook should only be used to add variables that depend on
	 * the current page/request; static configuration should be added through
	 * ResourceLoaderGetConfigVars instead.
	 *
	 * @since 1.35
	 *
	 * @param array &$vars Variable (or multiple variables) to be added into the output of
	 *   Skin::makeVariablesScript
	 * @param OutputPage $out OutputPage which called the hook, can be used to get the real title
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMakeGlobalVariablesScript( &$vars, $out );
}
