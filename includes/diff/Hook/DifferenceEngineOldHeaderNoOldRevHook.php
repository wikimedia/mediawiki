<?php

namespace MediaWiki\Diff\Hook;

/**
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
