<?php
/**
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

namespace MediaWiki\Installer;

use BagOStuff;
use MediaWiki\Config\Config;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MWCryptRand;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Send information about this MediaWiki instance to mediawiki.org.
 *
 * This service uses two kinds of rows in the `update_log` database table:
 *
 * - ul_key `PingBack`, this holds a random identifier for this wiki,
 *   created only once, when the first ping after wiki creation is sent.
 * - ul_key `Pingback-<MW_VERSION>`, this holds a timestamp and is created
 *   once after each MediaWiki upgrade, and then updated up to once a month.
 *
 * @internal For use by Setup.php only
 * @since 1.28
 */
class Pingback {
	/**
	 * @var int Revision ID of the JSON schema that describes the pingback payload.
	 * The schema lives on Meta-Wiki, at <https://meta.wikimedia.org/wiki/Schema:MediaWikiPingback>.
	 */
	private const SCHEMA_REV = 20104427;

	/** @var LoggerInterface */
	protected $logger;
	/** @var Config */
	protected $config;
	/** @var IConnectionProvider */
	protected $dbProvider;
	/** @var BagOStuff */
	protected $cache;
	/** @var HttpRequestFactory */
	protected $http;
	/** @var string updatelog key (also used as cache/db lock key) */
	protected $key;

	/**
	 * @param Config $config
	 * @param IConnectionProvider $dbProvider
	 * @param BagOStuff $cache
	 * @param HttpRequestFactory $http
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		Config $config,
		IConnectionProvider $dbProvider,
		BagOStuff $cache,
		HttpRequestFactory $http,
		LoggerInterface $logger
	) {
		$this->config = $config;
		$this->dbProvider = $dbProvider;
		$this->cache = $cache;
		$this->http = $http;
		$this->logger = $logger;
		$this->key = 'Pingback-' . MW_VERSION;
	}

	/**
	 * Maybe send a ping.
	 *
	 * @throws DBError If identifier insert fails
	 * @throws DBError If timestamp upsert fails
	 */
	public function run(): void {
		if ( !$this->config->get( MainConfigNames::Pingback ) ) {
			// disabled
			return;
		}
		if ( $this->wasRecentlySent() ) {
			// already sent recently
			return;
		}
		if ( !$this->acquireLock() ) {
			$this->logger->debug( __METHOD__ . ": couldn't acquire lock" );
			return;
		}

		$data = $this->getData();
		if ( !$this->postPingback( $data ) ) {
			$this->logger->warning( __METHOD__ . ": failed to send; check 'http' log channel" );
			return;
		}

		// Record the fact that we have sent a pingback for this MediaWiki version,
		// so we don't submit data multiple times.
		$dbw = $this->dbProvider->getPrimaryDatabase();
		$timestamp = ConvertibleTimestamp::time();
		$dbw->newInsertQueryBuilder()
			->insertInto( 'updatelog' )
			->row( [ 'ul_key' => $this->key, 'ul_value' => $timestamp ] )
			->onDuplicateKeyUpdate()
			->uniqueIndexFields( [ 'ul_key' ] )
			->set( [ 'ul_value' => $timestamp ] )
			->caller( __METHOD__ )->execute();
		$this->logger->debug( __METHOD__ . ": pingback sent OK ({$this->key})" );
	}

	/**
	 * Was a pingback sent in the last month for this MediaWiki version?
	 *
	 * @return bool
	 */
	private function wasRecentlySent(): bool {
		$timestamp = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( 'ul_value' )
			->from( 'updatelog' )
			->where( [ 'ul_key' => $this->key ] )
			->caller( __METHOD__ )->fetchField();
		if ( $timestamp === false ) {
			return false;
		}
		// send heartbeat ping if the last ping was over a month ago
		if ( ConvertibleTimestamp::time() - (int)$timestamp > 60 * 60 * 24 * 30 ) {
			return false;
		}
		return true;
	}

	/**
	 * Acquire lock for sending a pingback
	 *
	 * This ensures only one thread can attempt to send a pingback at any given
	 * time and that we wait an hour before retrying failed attempts.
	 *
	 * @return bool Whether lock was acquired
	 */
	private function acquireLock(): bool {
		$cacheKey = $this->cache->makeKey( 'pingback', $this->key );
		if ( !$this->cache->add( $cacheKey, 1, $this->cache::TTL_HOUR ) ) {
			// throttled
			return false;
		}

		$dbw = $this->dbProvider->getPrimaryDatabase();
		if ( !$dbw->lock( $this->key, __METHOD__, 0 ) ) {
			// already in progress
			return false;
		}

		return true;
	}

	/**
	 * Get the EventLogging packet to be sent to the server
	 *
	 * @throws DBError If identifier insert fails
	 * @return array
	 */
	protected function getData(): array {
		return [
			'schema' => 'MediaWikiPingback',
			'revision' => self::SCHEMA_REV,
			'wiki' => $this->fetchOrInsertId(),
			'event' => self::getSystemInfo( $this->config ),
		];
	}

	/**
	 * Collect basic data about this MediaWiki installation and return it
	 * as an associative array conforming to the Pingback schema on Meta-Wiki
	 * (<https://meta.wikimedia.org/wiki/Schema:MediaWikiPingback>).
	 *
	 * Developers: If you're adding a new piece of data to this, please document
	 * this data at <https://www.mediawiki.org/wiki/Manual:$wgPingback>.
	 *
	 * @internal For use by Installer only to display which data we send.
	 * @param Config $config With `DBtype` set.
	 * @return array
	 */
	public static function getSystemInfo( Config $config ): array {
		$event = [
			'database' => $config->get( MainConfigNames::DBtype ),
			'MediaWiki' => MW_VERSION,
			'PHP' => PHP_VERSION,
			'OS' => PHP_OS . ' ' . php_uname( 'r' ),
			'arch' => PHP_INT_SIZE === 8 ? 64 : 32,
			'machine' => php_uname( 'm' ),
		];

		if ( isset( $_SERVER['SERVER_SOFTWARE'] ) ) {
			$event['serverSoftware'] = $_SERVER['SERVER_SOFTWARE'];
		}

		$limit = ini_get( 'memory_limit' );
		if ( $limit && $limit !== "-1" ) {
			$event['memoryLimit'] = $limit;
		}

		return $event;
	}

	/**
	 * Get a unique, stable identifier for this wiki
	 *
	 * If the identifier does not already exist, create it and save it in the
	 * database. The identifier is randomly-generated.
	 *
	 * @throws DBError If identifier insert fails
	 * @return string 32-character hex string
	 */
	private function fetchOrInsertId(): string {
		// We've already obtained a primary connection for the lock, and plan to do a write.
		// But, still prefer reading this immutable value from a replica to reduce load.
		$id = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( 'ul_value' )
			->from( 'updatelog' )
			->where( [ 'ul_key' => 'PingBack' ] )
			->caller( __METHOD__ )->fetchField();
		if ( $id !== false ) {
			return $id;
		}

		$dbw = $this->dbProvider->getPrimaryDatabase();
		$id = $dbw->newSelectQueryBuilder()
			->select( 'ul_value' )
			->from( 'updatelog' )
			->where( [ 'ul_key' => 'PingBack' ] )
			->caller( __METHOD__ )->fetchField();
		if ( $id !== false ) {
			return $id;
		}

		$id = MWCryptRand::generateHex( 32 );
		$dbw->newInsertQueryBuilder()
			->insertInto( 'updatelog' )
			->row( [ 'ul_key' => 'PingBack', 'ul_value' => $id ] )
			->caller( __METHOD__ )->execute();
		return $id;
	}

	/**
	 * Serialize pingback data and send it to mediawiki.org via a POST request
	 * to its EventLogging beacon endpoint.
	 *
	 * The data encoding conforms to the expectations of EventLogging as used by
	 * Wikimedia Foundation for logging and processing analytic data.
	 *
	 * Compare:
	 * <https://gerrit.wikimedia.org/g/mediawiki/extensions/EventLogging/+/7e5fe4f1ef/includes/EventLogging.php#L32>
	 *
	 * The schema for the data is located at:
	 * <https://meta.wikimedia.org/wiki/Schema:MediaWikiPingback>
	 *
	 * @param array $data Pingback data as an associative array
	 * @return bool
	 */
	private function postPingback( array $data ): bool {
		$json = FormatJson::encode( $data );
		$queryString = rawurlencode( str_replace( ' ', '\u0020', $json ) ) . ';';
		$url = 'https://www.mediawiki.org/beacon/event?' . $queryString;
		return $this->http->post( $url, [], __METHOD__ ) !== null;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( Pingback::class, 'Pingback' );
