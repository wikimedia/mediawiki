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

use Psr\Log\LoggerInterface;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * Send information about this MediaWiki instance to MediaWiki.org.
 *
 * @since 1.28
 */
class Pingback {

	/**
	 * @var int Revision ID of the JSON schema that describes the pingback
	 *   payload. The schema lives on MetaWiki, at
	 *   <https://meta.wikimedia.org/wiki/Schema:MediaWikiPingback>.
	 */
	const SCHEMA_REV = 15781718;

	/** @var LoggerInterface */
	protected $logger;

	/** @var Config */
	protected $config;

	/** @var string updatelog key (also used as cache/db lock key) */
	protected $key;

	/** @var string Randomly-generated identifier for this wiki */
	protected $id;

	/**
	 * @param Config|null $config
	 * @param LoggerInterface|null $logger
	 */
	public function __construct( Config $config = null, LoggerInterface $logger = null ) {
		$this->config = $config ?: RequestContext::getMain()->getConfig();
		$this->logger = $logger ?: LoggerFactory::getInstance( __CLASS__ );
		$this->key = 'Pingback-' . $this->config->get( 'Version' );
	}

	/**
	 * Should a pingback be sent?
	 * @return bool
	 */
	private function shouldSend() {
		return $this->config->get( 'Pingback' ) && !$this->checkIfSent();
	}

	/**
	 * Has a pingback been sent in the last month for this MediaWiki version?
	 * @return bool
	 */
	private function checkIfSent() {
		$dbr = wfGetDB( DB_REPLICA );
		$timestamp = $dbr->selectField(
			'updatelog',
			'ul_value',
			[ 'ul_key' => $this->key ],
			__METHOD__
		);
		if ( $timestamp === false ) {
			return false;
		}
		// send heartbeat ping if last ping was over a month ago
		if ( time() - (int)$timestamp > 60 * 60 * 24 * 30 ) {
			return false;
		}
		return true;
	}

	/**
	 * Record the fact that we have sent a pingback for this MediaWiki version,
	 * to ensure we don't submit data multiple times.
	 */
	private function markSent() {
		$dbw = wfGetDB( DB_MASTER );
		$timestamp = time();
		return $dbw->upsert(
			'updatelog',
			[ 'ul_key' => $this->key, 'ul_value' => $timestamp ],
			[ 'ul_key' ],
			[ 'ul_value' => $timestamp ],
			__METHOD__
		);
	}

	/**
	 * Acquire lock for sending a pingback
	 *
	 * This ensures only one thread can attempt to send a pingback at any given
	 * time and that we wait an hour before retrying failed attempts.
	 *
	 * @return bool Whether lock was acquired
	 */
	private function acquireLock() {
		$cache = ObjectCache::getLocalClusterInstance();
		if ( !$cache->add( $this->key, 1, 60 * 60 ) ) {
			return false;  // throttled
		}

		$dbw = wfGetDB( DB_MASTER );
		if ( !$dbw->lock( $this->key, __METHOD__, 0 ) ) {
			return false;  // already in progress
		}

		return true;
	}

	/**
	 * Collect basic data about this MediaWiki installation and return it
	 * as an associative array conforming to the Pingback schema on MetaWiki
	 * (<https://meta.wikimedia.org/wiki/Schema:MediaWikiPingback>).
	 *
	 * This is public so we can display it in the installer
	 *
	 * Developers: If you're adding a new piece of data to this, please ensure
	 * that you update https://www.mediawiki.org/wiki/Manual:$wgPingback
	 *
	 * @return array
	 */
	public function getSystemInfo() {
		$event = [
			'database'   => $this->config->get( 'DBtype' ),
			'MediaWiki'  => $this->config->get( 'Version' ),
			'PHP'        => PHP_VERSION,
			'OS'         => PHP_OS . ' ' . php_uname( 'r' ),
			'arch'       => PHP_INT_SIZE === 8 ? 64 : 32,
			'machine'    => php_uname( 'm' ),
		];

		if ( isset( $_SERVER['SERVER_SOFTWARE'] ) ) {
			$event['serverSoftware'] = $_SERVER['SERVER_SOFTWARE'];
		}

		$limit = ini_get( 'memory_limit' );
		if ( $limit && $limit != -1 ) {
			$event['memoryLimit'] = $limit;
		}

		return $event;
	}

	/**
	 * Get the EventLogging packet to be sent to the server
	 *
	 * @return array
	 */
	private function getData() {
		return [
			'schema'           => 'MediaWikiPingback',
			'revision'         => self::SCHEMA_REV,
			'wiki'             => $this->getOrCreatePingbackId(),
			'event'            => $this->getSystemInfo(),
		];
	}

	/**
	 * Get a unique, stable identifier for this wiki
	 *
	 * If the identifier does not already exist, create it and save it in the
	 * database. The identifier is randomly-generated.
	 *
	 * @return string 32-character hex string
	 */
	private function getOrCreatePingbackId() {
		if ( !$this->id ) {
			$id = wfGetDB( DB_REPLICA )->selectField(
				'updatelog', 'ul_value', [ 'ul_key' => 'PingBack' ] );

			if ( $id == false ) {
				$id = MWCryptRand::generateHex( 32 );
				$dbw = wfGetDB( DB_MASTER );
				$dbw->insert(
					'updatelog',
					[ 'ul_key' => 'PingBack', 'ul_value' => $id ],
					__METHOD__,
					[ 'IGNORE' ]
				);

				if ( !$dbw->affectedRows() ) {
					$id = $dbw->selectField(
						'updatelog', 'ul_value', [ 'ul_key' => 'PingBack' ] );
				}
			}

			$this->id = $id;
		}

		return $this->id;
	}

	/**
	 * Serialize pingback data and send it to MediaWiki.org via a POST
	 * to its event beacon endpoint.
	 *
	 * The data encoding conforms to the expectations of EventLogging,
	 * a software suite used by the Wikimedia Foundation for logging and
	 * processing analytic data.
	 *
	 * Compare:
	 * <https://github.com/wikimedia/mediawiki-extensions-EventLogging/
	 *   blob/7e5fe4f1ef/includes/EventLogging.php#L32-L74>
	 *
	 * @param array $data Pingback data as an associative array
	 * @return bool true on success, false on failure
	 */
	private function postPingback( array $data ) {
		$json = FormatJson::encode( $data );
		$queryString = rawurlencode( str_replace( ' ', '\u0020', $json ) ) . ';';
		$url = 'https://www.mediawiki.org/beacon/event?' . $queryString;
		return MediaWikiServices::getInstance()->getHttpRequestFactory()->post( $url ) !== null;
	}

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
	 * @return bool
	 */
	public function sendPingback() {
		if ( !$this->acquireLock() ) {
			$this->logger->debug( __METHOD__ . ": couldn't acquire lock" );
			return false;
		}

		$data = $this->getData();
		if ( !$this->postPingback( $data ) ) {
			$this->logger->warning( __METHOD__ . ": failed to send pingback; check 'http' log" );
			return false;
		}

		$this->markSent();
		$this->logger->debug( __METHOD__ . ": pingback sent OK ({$this->key})" );
		return true;
	}

	/**
	 * Schedule a deferred callable that will check if a pingback should be
	 * sent and (if so) proceed to send it.
	 */
	public static function schedulePingback() {
		DeferredUpdates::addCallableUpdate( function () {
			$instance = new Pingback;
			if ( $instance->shouldSend() ) {
				$instance->sendPingback();
			}
		} );
	}
}
