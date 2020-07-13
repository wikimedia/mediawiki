<?php

namespace MediaWiki\Hook;

use WebRequest;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialLogAddLogSearchRelationsHook {
	/**
	 * Use this hook to add log relations to the current log
	 *
	 * @since 1.35
	 *
	 * @param string $type String of the log type
	 * @param WebRequest $request WebRequest object for getting the value provided by the current user
	 * @param array &$qc Array for query conditions to add
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialLogAddLogSearchRelations( $type, $request, &$qc );
}
