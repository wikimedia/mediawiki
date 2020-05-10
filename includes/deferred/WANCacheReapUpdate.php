<?php

use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IDatabase;

/**
 * Class for fixing stale WANObjectCache keys using a purge event source
 *
 * This is useful for expiring keys that missed fire-and-forget purges. This uses the
 * recentchanges table as a reliable stream to make certain keys reach consistency
 * as soon as the underlying replica database catches up. These means that critical
 * keys will not escape getting purged simply due to brief hiccups in the network,
 * which are more prone to happen across datacenters.
 *
 * ----
 * "I was trying to cheat death. I was only trying to surmount for a little while the
 * darkness that all my life I surely knew was going to come rolling in on me some day
 * and obliterate me. I was only to stay alive a little brief while longer, after I was
 * already gone. To stay in the light, to be with the living, a little while past my time."
 *   -- Notes for "Blues of a Lifetime", by [[Cornell Woolrich]]
 *
 * @since 1.28
 */
class WANCacheReapUpdate implements DeferrableUpdate {
	/** @var IDatabase */
	private $db;
	/** @var LoggerInterface */
	private $logger;

	/**
	 * @param IDatabase $db
	 * @param LoggerInterface $logger
	 */
	public function __construct( IDatabase $db, LoggerInterface $logger ) {
		$this->db = $db;
		$this->logger = $logger;
	}

	public function doUpdate() {
		$reaper = new WANObjectCacheReaper(
			MediaWikiServices::getInstance()->getMainWANObjectCache(),
			ObjectCache::getLocalClusterInstance(),
			[ $this, 'getTitleChangeEvents' ],
			[ $this, 'getEventAffectedKeys' ],
			[
				'channel' => 'table:recentchanges:' . $this->db->getDomainID(),
				'logger' => $this->logger
			]
		);

		$reaper->invoke( 100 );
	}

	/**
	 * @see WANObjectCacheRepear
	 *
	 * @param int $start
	 * @param int $id
	 * @param int $end
	 * @param int $limit
	 * @return TitleValue[]
	 */
	public function getTitleChangeEvents( $start, $id, $end, $limit ) {
		$db = $this->db;
		$encStart = $db->addQuotes( $db->timestamp( $start ) );
		$encEnd = $db->addQuotes( $db->timestamp( $end ) );
		$id = (int)$id; // cast NULL => 0 since rc_id is an integer

		$res = $db->select(
			'recentchanges',
			[ 'rc_namespace', 'rc_title', 'rc_timestamp', 'rc_id' ],
			[
				$db->makeList( [
					"rc_timestamp > $encStart",
					"rc_timestamp = $encStart AND rc_id > " . $db->addQuotes( $id )
				], LIST_OR ),
				"rc_timestamp < $encEnd"
			],
			__METHOD__,
			[ 'ORDER BY' => [ 'rc_timestamp ASC', 'rc_id ASC' ], 'LIMIT' => $limit ]
		);

		$events = [];
		foreach ( $res as $row ) {
			$events[] = [
				'id' => (int)$row->rc_id,
				'pos' => (int)wfTimestamp( TS_UNIX, $row->rc_timestamp ),
				'item' => new TitleValue( (int)$row->rc_namespace, $row->rc_title )
			];
		}

		return $events;
	}

	/**
	 * Gets a list of important cache keys associated with a title
	 *
	 * @see WANObjectCacheRepear
	 * @param WANObjectCache $cache
	 * @param LinkTarget $t
	 * @return string[]
	 */
	public function getEventAffectedKeys( WANObjectCache $cache, LinkTarget $t ) {
		/** @var WikiPage[]|LocalFile[]|User[] $entities */
		$entities = [];

		// You can't create a WikiPage for special pages (-1) or other virtual
		// namespaces, but special pages do appear in RC sometimes, e.g. for logs
		// of AbuseFilter filter changes.
		if ( $t->getNamespace() >= 0 ) {
			$entities[] = WikiPage::factory( Title::newFromLinkTarget( $t ) );
		}

		if ( $t->inNamespace( NS_FILE ) ) {
			$entities[] = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
				->newFile( $t->getText() );
		}
		if ( $t->inNamespace( NS_USER ) ) {
			$entities[] = User::newFromName( $t->getText(), false );
		}

		$keys = [];
		foreach ( $entities as $entity ) {
			if ( $entity ) {
				$keys = array_merge( $keys, $entity->getMutableCacheKeys( $cache ) );
			}
		}
		if ( $keys ) {
			$this->logger->debug( __CLASS__ . ': got key(s) ' . implode( ', ', $keys ) );
		}

		return $keys;
	}
}
