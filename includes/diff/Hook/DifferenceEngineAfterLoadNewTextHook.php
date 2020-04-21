<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DifferenceEngineAfterLoadNewTextHook {
	/**
	 * called in DifferenceEngine::loadNewText()
	 * after the new revision's content has been loaded into the class member variable
	 * $differenceEngine->mNewContent but before returning true from this function.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $differenceEngine DifferenceEngine object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineAfterLoadNewText( $differenceEngine );
}
