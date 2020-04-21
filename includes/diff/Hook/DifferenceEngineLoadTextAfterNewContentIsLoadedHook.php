<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DifferenceEngineLoadTextAfterNewContentIsLoadedHook {
	/**
	 * called in
	 * DifferenceEngine::loadText() after the new revision's content has been loaded
	 * into the class member variable $differenceEngine->mNewContent but before
	 * checking if the variable's value is null.
	 * This hook can be used to inject content into said class member variable.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $differenceEngine DifferenceEngine object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineLoadTextAfterNewContentIsLoaded(
		$differenceEngine
	);
}
