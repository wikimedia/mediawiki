<?php
interface RCFeedFormatter {
	/**
	 * Formats the line for the live feed.
	 *
	 * @param array $feed The feed, as configured in an associative array.
	 * @param RecentChange $rc The RecentChange object showing what sort
	 *                         of event has taken place.
	 * @param string|null $actionComment
	 * @return string The text to send.
	 */
	public function getLine( array $feed, RecentChange $rc, $actionComment );
}
