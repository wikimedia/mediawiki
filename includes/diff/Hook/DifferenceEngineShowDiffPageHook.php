<?php

namespace MediaWiki\Diff\Hook;

use MediaWiki\Output\OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "DifferenceEngineShowDiffPage" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineShowDiffPageHook {
	/**
	 * Use this hook to add additional output via the available OutputPage object into the diff view.
	 *
	 * @since 1.35
	 *
	 * @param OutputPage $out
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineShowDiffPage( $out );
}
