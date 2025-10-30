<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logger\Monolog;

use MediaWiki\Http\Telemetry;
use MediaWiki\WikiMap\WikiMap;

/**
 * Annotate log records with request-global metadata, such as the hostname,
 * wiki / request ID, and MediaWiki version.
 *
 * @since 1.25
 * @ingroup Debug
 * @copyright © 2013 Wikimedia Foundation and contributors
 */
class WikiProcessor {

	/**
	 * @param array $record
	 * @return array
	 */
	public function __invoke( array $record ) {
		$telemetry = Telemetry::getInstance();
		$record['extra']['host'] = wfHostname();
		$record['extra']['wiki'] = WikiMap::getCurrentWikiId();
		$record['extra']['mwversion'] = MW_VERSION;
		$record['extra']['reqId'] = $telemetry->getRequestId();
		if ( wfIsCLI() && isset( $_SERVER['argv'] ) ) {
			$record['extra']['cli_argv'] = implode( ' ', $_SERVER['argv'] );
		}
		return $record;
	}

}
