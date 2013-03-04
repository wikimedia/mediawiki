<?php
interface RCFeedEngine {
	/**
	 * Sends some text to the specified live feed.
	 *
	 * @see RecentChange::cleanupForIRC
	 * @param $line string The text to send.
	 * @param $feed array The feed, as configured in an associative array.
	 * @return boolean success
	 */
	public function send( $line, array $feed );
}
