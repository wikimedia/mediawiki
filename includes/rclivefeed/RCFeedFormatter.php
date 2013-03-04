<?php
interface RCFeedFormatter {
	/**
	 * Formats the line for the live feed.
	 *
	 * @param $feed array The feed, as configured in an associative array.
	 * @param $rc RecentChange The RecentChange object showing what sort
	 *                         of event has taken place.
	 * @param $actionComment string|null
	 * @return string The text to send.
	 */
	public function getLine( array $feed, RecentChange $rc, $actionComment );
}
