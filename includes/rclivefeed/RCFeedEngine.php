<?php
abstract class RCFeedEngine {
	/**
	 * Formats the line for the live feed.
	 *
	 * @param $feed array
	 * @param $rc RecentChange
	 * @param $actionComment string
	 * @return string
	 */
	public abstract function getLine( $feed, $rc, $actionComment );

	/**
	 * Send some text.
	 * @see RecentChange::cleanupForIRC
	 * @param $line string Text to send
	 * @param $feed array
	 * @param $prefix string
	 * @return boolean success
	 */
	public abstract function send( $line, $feed, $prefix );
}
