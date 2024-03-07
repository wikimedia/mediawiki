<?php

namespace MediaWiki\Output\Hook;

use MediaWiki\Output\OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MakeGlobalVariablesScript" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MakeGlobalVariablesScriptHook {
	/**
	 * Export user- or page-specific `mw.config` variables to JavaScript.
	 *
	 * When using this hook, be as selective as possible about when the data is set.
	 * Reduce the cost by setting values only for specific titles, namespaces, or user-rights.
	 *
	 * Data exported here is transmitted with the highest possible bandwidth priority (ahead of
	 * page content even). Any data that is not dependant on the current request, should go
	 * through MediaWiki\ResourceLoader\Hook\ResourceLoaderGetConfigVarsHook instead.
	 *
	 * This hook is called from OutputPage::getJSVars.
	 *
	 * @since 1.35
	 *
	 * @param array &$vars Variable (or multiple variables)
	 * @param OutputPage $out OutputPage which called the hook, can be used to get the real title
	 * @return void This hook must not abort, it must return no value
	 */
	public function onMakeGlobalVariablesScript( &$vars, $out ): void;
}

/** @deprecated class alias since 1.41 */
class_alias( MakeGlobalVariablesScriptHook::class, 'MediaWiki\Hook\MakeGlobalVariablesScriptHook' );
