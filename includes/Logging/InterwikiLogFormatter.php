<?php

namespace MediaWiki\Logging;

use MediaWiki\Message\Message;

/**
 * LogFormatter for interwiki/* logs
 *
 * @since 1.44
 */
class InterwikiLogFormatter extends LogFormatter {
	/**
	 * @return array
	 * @suppress SecurityCheck-DoubleEscaped taint-check bug
	 */
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		// Needed to pass the URL as a raw parameter, because it contains $1
		if ( isset( $params[4] ) ) {
			$params[4] = Message::rawParam( htmlspecialchars( $params[4] ) );
		}
		return $params;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( InterwikiLogFormatter::class, 'InterwikiLogFormatter' );
