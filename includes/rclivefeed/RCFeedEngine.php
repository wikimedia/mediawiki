<?php
interface RCFeedEngine {
	/**
	 * Send some text.
	 *
	 * @see RecentChange::cleanupForIRC
	 * @param $line string Text to send
	 * @param $feed array
	 * @param $prefix string
	 * @return boolean success
	 */
	public function send( $line, $feed, $prefix );
}
