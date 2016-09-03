<?php

/**
 * Class for fixing bad key entries in WANObjectCache from an event source
 *
 * @since 1.28
 */
class WANCacheReapUpdate implements DeferrableUpdate {
	/** @var IDatabase */
	private $db;

	/**
	 * @param IDatabase $db
	 */
	public function __construct( IDatabase $db ) {
		$this->db = $db;
	}

	function doUpdate() {
		$reaper = new WANObjectCacheRepear(
			ObjectCache::getMainWANInstance(),
			ObjectCache::getLocalClusterInstance(),
			[ $this, 'getChangedTitles' ],
			[ $this, 'getAffectedKeys' ],
			[
				'channel' => 'table:recentchanges:' . $this->db->getWikiID(),
				'logger' => \MediaWiki\Logger\LoggerFactory::getInstance( 'objectcache' )
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
	public function getChangedTitles( $start, $id, $end, $limit ) {
		$db = $this->db;
		$encStart = $db->addQuotes( $db->timestamp( $start ) );
		$encEnd = $db->addQuotes( $db->timestamp( $end ) );

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
			[ 'ORDER BY' => 'rc_timestamp ASC, rc_id ASC', 'LIMIT' => $limit ]
		);

		$events = [];
		foreach ( $res as $row ) {
			$events[] = [
				'id' => (int)$row->rc_id,
				'pos' => wfTimestamp( TS_UNIX, $row->rc_timestamp ),
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
	 * @param TitleValue $t
	 * @returns string[]
	 */
	public function getAffectedKeys( WANObjectCache $cache, TitleValue $t ) {
		/** @var WikiPage[]|LocalFile[]|User[] $entities */
		$entities = [];

		$entities[] = WikiPage::factory( Title::newFromTitleValue( $t ) );
		if ( $t->inNamespace( NS_FILE ) ) {
			$entities[] = wfLocalFile( $t->getText() );
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
			wfDebugLog( 'objectcache', __CLASS__ . ': got key(s) ' . implode( ', ', $keys ) );
		}

		return $keys;
	}
}
