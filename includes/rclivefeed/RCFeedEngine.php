<?php
interface RCFeedEngine {
	/**
	 * Sends some text to the specified live feed.
	 *
	 * @see RecentChange::cleanupForIRC
	 * @param string $line The text to send.
	 * @param array $feed The feed, as configured in an associative array.
	 * @return boolean success
	 */
	public function send( $line, array $feed );
}
