<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialLogAddLogSearchRelationsHook {
	/**
	 * Add log relations to the current log
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $type String of the log type
	 * @param ?mixed $request WebRequest object for getting the value provided by the current user
	 * @param ?mixed &$qc Array for query conditions to add
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialLogAddLogSearchRelations( $type, $request, &$qc );
}
