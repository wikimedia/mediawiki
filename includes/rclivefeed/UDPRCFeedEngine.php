<?php
class UDPRCFeedEngine implements RCFeedEngine {
	public function send( $line, $feed, $prefix ) {
		wfLogToFileOrStream( $prefix . $line, $feed['uri'] );
	}
}
