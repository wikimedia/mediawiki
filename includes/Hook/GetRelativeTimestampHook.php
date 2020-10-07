<?php

namespace MediaWiki\Hook;

use DateInterval;
use Language;
use MWTimestamp;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface GetRelativeTimestampHook {
	/**
	 * Use this hook to pre-emptively override the relative timestamp generated
	 * by MWTimestamp::getRelativeTimestamp().
	 *
	 * @since 1.35
	 *
	 * @param string &$output String for the output timestamp
	 * @param DateInterval &$diff Difference between the timestamps
	 * @param MWTimestamp $timestamp Current (user-adjusted) timestamp
	 * @param MWTimestamp $relativeTo Relative (user-adjusted) timestamp
	 * @param User $user User whose preferences are being used to make timestamp
	 * @param Language $lang Language that will be used to render the timestamp
	 * @return bool|void True or no return value to continue, or false to use the custom output
	 */
	public function onGetRelativeTimestamp( &$output, &$diff, $timestamp,
		$relativeTo, $user, $lang
	);
}
