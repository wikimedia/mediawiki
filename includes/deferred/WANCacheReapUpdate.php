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
				'channel' => 'table:recentchanges',
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
	 * @return array
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
	 * @TODO: avoid key generation code duplication
	 */
	public function getAffectedKeys( WANObjectCache $cache, TitleValue $t ) {
		$keys = [];
		if ( $t->inNamespace( NS_FILE ) ) {
			/** @var LocalFile $file */
			$file = RepoGroup::singleton()->getLocalRepo()->newFile( $t->getDBkey() );
			$keys[] = $file->getCacheKey();
		}

		if ( $t->inNamespace( NS_FILE ) || $t->inNamespace( NS_TEMPLATE ) ) {
			$keys[] = $cache->makeKey( 'page', $t->getNamespace(), sha1( $t->getDBkey() ) );
		}

		if ( $t->inNamespace( NS_USER ) || $t->inNamespace( NS_USER_TALK ) ) {
			$id = User::idFromName( $t->getDBkey() );
			if ( $id ) {
				$keys[] = $cache->makeGlobalKey( 'user', 'id', wfWikiID(), $id );
			}
		}

		if ( $keys ) {
			wfDebugLog( 'objectcache', __CLASS__ . ': checking key(s) ' . implode( ', ', $keys ) );
		}

		return $keys;
	}
}
