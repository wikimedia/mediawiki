<?php

namespace MediaWiki\Logger\Monolog;

/**
 * CeeFormatter extends LogstashFormatter to prefix records with a "cee cookie".
 * The cookie is used to tell JSON and non-JSON messages apart when logging to syslog.
 * See also: https://www.rsyslog.com/doc/v8-stable/configuration/modules/mmjsonparse.html
 *
 * @since 1.33
 */
class CeeFormatter extends LogstashFormatter {
	/**
	 * Format records with a cee cookie
	 * @param array $record
	 * @return string
	 */
	public function format( array $record ): string {
		return "@cee: " . parent::format( $record );
	}
}
