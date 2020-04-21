<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialStatsAddExtraHook {
	/**
	 * Add extra statistic at the end of Special:Statistics.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$extraStats Array to save the new stats
	 *   	$extraStats['<name of statistic>'] => <value>;
	 *   <value> can be an array with the keys "name" and "number":
	 *   "name" is the HTML to be displayed in the name column
	 *   "number" is the number to be displayed.
	 *   or, <value> can be the number to be displayed and <name> is the
	 *   message key to use in the name column,
	 * @param ?mixed $context IContextSource object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialStatsAddExtra( &$extraStats, $context );
}
