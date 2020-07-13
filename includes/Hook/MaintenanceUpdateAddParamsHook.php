<?php

namespace MediaWiki\Hook;

// phpcs:disable Generic.Files.LineLength -- Remove this after doc review
/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MaintenanceUpdateAddParamsHook {
	/**
	 * Use this hook to add params to the update.php maintenance script.
	 *
	 * @since 1.35
	 *
	 * @param array &$params Array to populate with the params to be added. Array elements are keyed by
	 *   the param name. Each param is an associative array that must include the following keys:
	 *   - `desc`: The description of the param to show on --help
	 *   - `require`: Is the param required? Defaults to false if not set.
	 *   - `withArg`: Is an argument required with this option?  Defaults to false if not set.
	 *   - `shortName`: Character to use as short name, or false if none.  Defaults to false if not set.
	 *   - `multiOccurrence`: Can this option be passed multiple times?  Defaults to false if not set.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMaintenanceUpdateAddParams( &$params );
}
