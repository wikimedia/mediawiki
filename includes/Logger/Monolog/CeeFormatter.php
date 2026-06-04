<?php

namespace MediaWiki\Logger\Monolog;

use Monolog\LogRecord;

/**
 * Prefixed version of LogstashFormatter that adds a "cee cookie" for Rsyslog.
 *
 * The prefix lets Ryslog differentiate between JSON and non-JSON messages.
 *
 * See also: https://www.rsyslog.com/doc/v8-stable/configuration/modules/mmjsonparse.html
 *
 * @since 1.33
 * @ingroup Debug
 */
class CeeFormatter extends LogstashFormatter {
	/**
	 * Format records with a cee cookie
	 * @param array|LogRecord $record
	 * @return string
	 */
	public function format( array|LogRecord $record ): string {
		return "@cee: " . parent::format( $record );
	}
}
