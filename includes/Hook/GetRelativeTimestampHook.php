<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetRelativeTimestampHook {
	/**
	 * Pre-emptively override the relative timestamp generated
	 * by MWTimestamp::getRelativeTimestamp(). Return false in this hook to use the
	 * custom output.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$output string for the output timestamp
	 * @param ?mixed &$diff DateInterval representing the difference between the timestamps
	 * @param ?mixed $timestamp MWTimestamp object of the current (user-adjusted) timestamp
	 * @param ?mixed $relativeTo MWTimestamp object of the relative (user-adjusted) timestamp
	 * @param ?mixed $user User whose preferences are being used to make timestamp
	 * @param ?mixed $lang Language that will be used to render the timestamp
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetRelativeTimestamp( &$output, &$diff, $timestamp,
		$relativeTo, $user, $lang
	);
}
