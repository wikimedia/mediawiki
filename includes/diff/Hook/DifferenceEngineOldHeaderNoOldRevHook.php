<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DifferenceEngineOldHeaderNoOldRevHook {
	/**
	 * Change the $oldHeader variable in cases
	 * when there is no old revision
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$oldHeader empty string by default
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineOldHeaderNoOldRev( &$oldHeader );
}
