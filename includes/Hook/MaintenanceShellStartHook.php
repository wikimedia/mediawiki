<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MaintenanceShellStartHook {
	/**
	 * This hook is called before maintenance script shells start, such as eval.php and shell.php
	 *
	 * @since 1.36
	 * @return void This hook must not abort, it must return no value
	 */
	public function onMaintenanceShellStart() : void;
}
