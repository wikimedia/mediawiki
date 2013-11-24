<?php
interface RCFeedEngine {
	/**
	 * Sends some text to the specified live feed.
	 *
	 * @see RecentChange::cleanupForIRC
	 * @param array $feed The feed, as configured in an associative array.
	 * @param string $line The text to send.
	 * @return boolean success
	 */
	public function send( array $feed, $line );
}
