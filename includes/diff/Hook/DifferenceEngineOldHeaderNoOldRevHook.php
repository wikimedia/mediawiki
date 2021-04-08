<?php

namespace MediaWiki\Diff\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "DifferenceEngineOldHeaderNoOldRev" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineOldHeaderNoOldRevHook {
	/**
	 * Use this hook to change the $oldHeader variable in cases when there is no old revision
	 *
	 * @since 1.35
	 *
	 * @param string &$oldHeader Empty string by default
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineOldHeaderNoOldRev( &$oldHeader );
}
