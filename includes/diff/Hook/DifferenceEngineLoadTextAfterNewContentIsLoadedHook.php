<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "DifferenceEngineLoadTextAfterNewContentIsLoaded" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineLoadTextAfterNewContentIsLoadedHook {
	/**
	 * This hook is called in DifferenceEngine::loadText() after the new revision's content
	 * has been loaded into the class member variable $differenceEngine->mNewContent but before
	 * checking if the variable's value is null. Use this hook to inject content into said
	 * class member variable.
	 *
	 * @since 1.35
	 *
	 * @param DifferenceEngine $differenceEngine
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineLoadTextAfterNewContentIsLoaded(
		$differenceEngine
	);
}
