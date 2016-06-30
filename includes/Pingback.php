<?php
/**
 * Send information about this MediaWiki instance to MediaWiki.org.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Send information about this MediaWiki instance to MediaWiki.org.
 *
 * @since 1.28
 */
class Pingback {

	/**
	 * @var int Revision ID of the JSON schema that describes the pingback
	 *   payload. The schema lives on MetaWiki, at
	 *   https://meta.wikimedia.org/wiki/Schema:MediaWikiPingback.
	 */
	const SCHEMA_REV = 15777149;

	/**
	 * Send information about this MediaWiki instance to MediaWiki.org.
	 *
	 * The data is structured and serialized to match the expectations of
	 * EventLogging, a software suite used by the Wikimedia Foundation for
	 * logging and processing analytic data.
	 *
	 * Compare:
	 * <https://github.com/wikimedia/mediawiki-extensions-EventLogging/
	 *   blob/7e5fe4f1ef/includes/EventLogging.php#L32-L74>
	 *
	 * The schema for the data is located at:
	 * <https://meta.wikimedia.org/wiki/Schema:MediaWikiPingback>
	 */
	public static function sendPingback() {
		global $IP;

		$config = RequestContext::getMain()->getConfig();

		if ( !$config->get( 'Pingback' ) ) {
			return false;  // disabled by configuration
		}

		$key = 'Pingback-' . $config->get( 'Version' );

		$dbr = wfGetDB( DB_SLAVE );
		$sent = $dbr->selectField( 'updatelog', '1', [ 'ul_key' => $key ] );
		if ( $sent !== false ) {
			wfDebug( __METHOD__ . ": Not sending pingback; already sent for this version.\n" );
			return false;  // already sent
		}

		$dbw = wfGetDB( DB_MASTER );
		if ( !$dbw->lock( $key, __METHOD__, 0 ) ) {
			wfDebug( __METHOD__ . ": Not sending pingback; another attempt is already in progress.\n" );
			return false;  // already in progress
		}

		$cache = ObjectCache::getLocalClusterInstance();
		if ( !$cache->add( $key, 1, 60 * 60 ) ) {
			wfDebug( __METHOD__ . ": Not sending pingback; throttled.\n" );
			return false;  // throttled after a failed attempt
		}

		$event = [
			'database'   => $config->get( 'DBtype' ),
			'MediaWiki'  => $config->get( 'Version' ),
			'PHP'        => PHP_VERSION,
			'OS'         => PHP_OS . ' ' . php_uname( 'r' ),
			'arch'       => PHP_INT_SIZE === 8 ? 64 : 32,
			'machine'    => php_uname( 'm' ),
			'usesGit'    => file_exists( $IP . '/.git' ),
		];

		if ( isset( $_SERVER['SERVER_SOFTWARE'] ) ) {
			$event['serverSoftware'] = $_SERVER['SERVER_SOFTWARE'];
		}

		$limit = ini_get( 'memory_limit' );
		if ( $limit && $limit != -1 ) {
			$event['memoryLimit'] = $limit;
		}

		$json = FormatJson::encode( [
			'schema'           => 'MediaWikiPingback',
			'revision'         => self::SCHEMA_REV,
			'wiki'             => $config->get( 'DBname' ),
			'event'            => $event,
			'webHost'          => $config->get( 'Server' ),
		] );

		wfDebug( __METHOD__ . ": sending pingback with data: {$json}\n" );

		$json = str_replace( ' ', '\u0020', $json );
		$url = 'https://www.mediawiki.org/beacon/event?' . rawurlencode( $json ) . ';';
		if ( Http::post( $url ) !== false ) {
			$dbw->insert( 'updatelog', [ 'ul_key' => $key ], __METHOD__, 'IGNORE' );
		}

		$dbw->unlock( $key, __METHOD__ );
	}
}
