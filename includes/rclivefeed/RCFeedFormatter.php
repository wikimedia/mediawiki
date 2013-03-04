<?php
interface RCFeedFormatter {
	/**
	 * Formats the line for the live feed.
	 *
	 * @param $feed array
	 * @param $rc RecentChange
	 * @param $actionComment string
	 * @return string
	 */
	public function getLine( $feed, $rc, $actionComment );
}
