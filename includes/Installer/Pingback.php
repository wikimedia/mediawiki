<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Installer;

use MediaWiki\Config\Config;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MWCryptRand;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\BagOStuff;
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
	 * @var string The name of the Legacy EventLogging schema that Pingback used to use.
	 */
	private const LEGACY_EVENTLOGGING_SCHEMA = 'MediaWikiPingback';

	/**
	 * @var string The versioned schema with which the Pingback events will be validated.
	 *
	 * All versions of the schema live at
	 * {@link https://schema.wikimedia.org/#!//secondary/jsonschema/analytics/legacy/mediawikipingback}.
	 */
	private const EVENT_PLATFORM_SCHEMA_ID = '/analytics/legacy/mediawikipingback/1.0.0';

	/**
	 * @var string The name of the Event Platform stream to submit the event to.
	 *
	 * By convention, we derive the name of an Event Platform stream corresponding to a Legacy
	 * EventLogging schema by prepending "eventlogging_" to it, i.e.
	 * "FooSchema" -> "eventlogging_FooSchema". This convention is codified in
	 * {@link https://gerrit.wikimedia.org/g/mediawiki/extensions/EventLogging/+/d47dbc10455bcb6dbc98a49fa169f75d6131c3da/includes/EventLogging.php#298}.
	 *
	 * @see Pingback::LEGACY_EVENTLOGGING_SCHEMA
	 */
	private const EVENT_PLATFORM_STREAM = 'eventlogging_MediaWikiPingback';

	/** @var string */
	private const EVENT_PLATFORM_EVENT_INTAKE_SERVICE_URI =
		'https://intake-analytics.wikimedia.org/v1/events?hasty=true';

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
	/** @var string */
	protected $eventIntakeUri;

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
		LoggerInterface $logger,
		string $eventIntakeUrl = self::EVENT_PLATFORM_EVENT_INTAKE_SERVICE_URI
	) {
		$this->config = $config;
		$this->dbProvider = $dbProvider;
		$this->cache = $cache;
		$this->http = $http;
		$this->logger = $logger;
		$this->key = 'Pingback-' . MW_VERSION;
		$this->eventIntakeUri = $eventIntakeUrl;
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
	 * Get the event to be sent to the server.
	 *
	 * Note well that, as well as the pingback data, only those fields required by the Event Platform are set (see
	 * <https://wikitech.wikimedia.org/wiki/Event_Platform/Schemas/Guidelines#Required_fields>).
	 *
	 * @throws DBError If identifier insert fails
	 * @return array
	 */
	protected function getData(): array {
		$wiki = $this->fetchOrInsertId();

		return [
			'event' => self::getSystemInfo( $this->config ),
			'schema' => self::LEGACY_EVENTLOGGING_SCHEMA,
			'wiki' => $wiki,

			// This would be added by
			// https://gerrit.wikimedia.org/g/mediawiki/extensions/EventLogging/+/d47dbc10455bcb6dbc98a49fa169f75d6131c3da/includes/EventLogging.php#274
			// onwards.
			'$schema' => self::EVENT_PLATFORM_SCHEMA_ID,
			'client_dt' => ConvertibleTimestamp::now( TS_ISO_8601 ),

			// This would be added by
			// https://gerrit.wikimedia.org/r/plugins/gitiles/mediawiki/extensions/EventLogging/+/d47dbc10455bcb6dbc98a49fa169f75d6131c3da/includes/EventSubmitter/EventBusEventSubmitter.php#81
			// onwards.
			'meta' => [
				'stream' => self::EVENT_PLATFORM_STREAM,
			],
		];
	}

	/**
	 * Collect basic data about this MediaWiki installation and return it
	 * as an associative array conforming to the MediaWikiPingback event schema at
	 * <https://gerrit.wikimedia.org/r/plugins/gitiles/schemas/event/secondary/+/refs/heads/master/jsonschema/analytics/legacy/mediawikipingback/>.
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
	 * Serialize the pingback data and submit it to the Event Platform (see
	 * <https://wikitech.wikimedia.org/wiki/Event_Platform>).
	 *
	 * Compare:
	 * <https://gerrit.wikimedia.org/r/plugins/gitiles/mediawiki/extensions/EventLogging/+/933b62f29d68f/includes/EventSubmitter/EventBusEventSubmitter.php#33>
	 *
	 * The schema for the event is located at:
	 * <https://schema.wikimedia.org/repositories/secondary/jsonschema/analytics/legacy/mediawikipingback/1.0.0>
	 *
	 * @param array $data Pingback data as an associative array
	 * @return bool
	 */
	private function postPingback( array $data ): bool {
		$request = $this->http->create( $this->eventIntakeUri, [
			'method' => 'POST',
			'postData' => FormatJson::encode( $data ),
		], __METHOD__ );
		$request->setHeader( 'Content-Type', 'application/json' );

		$result = $request->execute();

		return $result->isGood();
	}
}

/** @deprecated class alias since 1.41 */
class_alias( Pingback::class, 'Pingback' );
