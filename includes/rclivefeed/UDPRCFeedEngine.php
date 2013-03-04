<?php
class UDPRCFeedEngine implements RCFeedEngine {
	/**
	 * Sends the notification to the specified host in a UDP packet.
	 * @see RCFeedEngine::send
	 */
	public function send( $line, array $feed ) {
		wfErrorLog( $line, $feed['uri'] );
	}
}
