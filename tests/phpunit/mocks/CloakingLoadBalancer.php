<?php
use Wikimedia\Assert\Assert;

/**
 * A cloaking LoadBalancer.
 *
 * This class allows database connections to be "cloaked" by applying a table prefix.
 * It also prevents access to any additionally configured databases, to avoid test
 * runs interfering with live data.
 *
 * @since 1.27
 */
class CloakingLoadBalancer extends LoadBalancer {

	/**
	 * @var array|null
	 */
	private $cloakParams = null;

	/**
	 * CloakingLoadBalancer constructor.
	 *
	 * @note only the first entry in $params['servers'] will be used!
	 *
	 * @param array $params
	 *
	 * @throws MWException
	 */
	public function __construct( array $params ) {
		// Use only one database for testing
		$params['servers'] = array_slice( $params['servers'], 0, 1 );

		parent::__construct( $params );
	}

	/**
	 * @return bool
	 */
	public function isCloaked() {
		return $this->cloakParams !== null;
	}

	/**
	 * Cloaks the database layer. LoadBalancer instances stay intact.
	 * All open connections are closed. New connections will automatically use
	 * cloaking according to $cloakParams.
	 *
	 * @see UnitTestLoadBalancer::cloakDatabase
	 *
	 * @param array $cloakParams defines keys: testDbPrefix: string, useTemporaryTables: bool,
	 *        reuseDB: bool.
	 *
	 * @throws MWException
	 */
	public function cloakDatabase( array $cloakParams ) {
		Assert::parameter(
			isset( $cloakParams['testDbPrefix'] ),
			'$cloakParams',
			'Must define key `testDbPrefix`'
		);
		Assert::parameter(
			isset( $cloakParams['useTemporaryTables'] ),
			'$cloakParams',
			'Must define key `useTemporaryTables`'
		);
		Assert::parameter(
			isset( $cloakParams['reuseDB'] ),
			'$cloakParams',
			'Must define key `reuseDB`'
		);

		$this->closeAll();
		$this->cloakParams = $cloakParams;
	}

	/**
	 * Uncloaks the database layer. LoadBalancer instances stay intact.
	 * All open connections are closed. New connections will function without cloaking.
	 *
	 * @see UnitTestLoadBalancer::uncloakDatabase
	 */
	public function uncloakDatabase() {
		if ( !$this->isCloaked() ) {
			return;
		}

		$this->cloakParams = null;
		$this->closeAll();
	}

	/**
	 * @param DatabaseBase $db
	 *
	 * @throws DBReadOnlyError
	 * @throws MWException
	 */
	private function cloakConnection( DatabaseBase $db ) {
		if ( isset( $db->_cloner ) ) {
			// $db already has a cloner glued on, so it's already cloaked.
			return;
		}

		$testDbPrefix = $this->cloakParams['testDbPrefix'];
		$useTemporaryTables = $this->cloakParams['useTemporaryTables'];
		$reuseDB = $this->cloakParams['reuseDB'];

		if ( $db->tablePrefix() === $testDbPrefix ) {
			throw new MWException(
				'Cannot cloak connection, the database prefix is already "' . $testDbPrefix . '"' );
		}

		$tablesCloned = $this->listTablesToClone( $db, $db->tablePrefix(), $testDbPrefix );
		$dbClone = new CloneDatabase( $db, $tablesCloned, $testDbPrefix );
		$dbClone->useTemporaryTables( $useTemporaryTables );

		// Hack: just glue $dbClone to the connection object, so we can use it for cleanup.,
		$db->_cloner = $dbClone;

		if ( ( $db->getType() == 'oracle' || !$useTemporaryTables ) && $reuseDB ) {
			$db->tablePrefix( $testDbPrefix );

			return;
		} else {
			$dbClone->cloneTableStructure();
		}

		if ( $db->getType() == 'oracle' ) {
			$db->query( 'BEGIN FILL_WIKI_INFO; END;' );
		}
	}

	private function cleanupConnection( DatabaseBase $db ) {
		// NOTE: It's unclear whether we'd want to drop tables here if we created them.
		// The old behavior was to never drop tables.
		// $dropTables = !$this->cloakParams['reuseDB'] && !$this->cloakParams['useTemporaryTables'];
		$dropTables = false;

		// NOTE: $db->_cloner is glued onto the $db object by setUpTestDB()
		if ( isset( $db->_cloner ) ) {
			$db->_cloner->destroy( $dropTables );
		}
		unset( $db->_cloner );
	}

	/**
	 * @param DatabaseBase $conn
	 */
	public function closeConnection( $conn ) {
		$this->cleanupConnection( $conn );
		parent::closeConnection( $conn );
	}

	/**
	 * Close all open connections
	 */
	public function closeAll() {
		$this->forEachOpenConnection( function( DatabaseBase $db ) {
			$this->cleanupConnection( $db );
		} );

		parent::closeAll();
	}

	/**
	 * @param DatabaseBase $db
	 *
	 * @return array
	 */
	private function listTablesToClone( DatabaseBase $db, $oldPrefix, $newPrefix ) {
		$prefix = $db->tablePrefix();

		$tables = $db->listTables( $prefix, __METHOD__ );

		if ( $db->getType() === 'mysql' ) {
			# bug 43571: cannot clone VIEWs under MySQL
			$views = $db->listViews( $prefix, __METHOD__ );
			$tables = array_diff( $tables, $views );
		}
		$tables = array_map( function( $tableName ) use ( $oldPrefix ) {
			return substr( $tableName, strlen( $oldPrefix ) );
		}, $tables );

		// Don't duplicate test tables from the previous fataled run
		$tables = array_filter( $tables, function( $tableName ) use ( $newPrefix ) {
			return strpos( $tableName, $newPrefix ) !== 0;
		} );

		if ( $db->getType() == 'sqlite' ) {
			$tables = array_flip( $tables );
			// these are subtables of searchindex and don't need to be duped/dropped separately
			unset( $tables['searchindex_content'] );
			unset( $tables['searchindex_segdir'] );
			unset( $tables['searchindex_segments'] );
			$tables = array_flip( $tables );
		}

		return $tables;
	}

	/**
	 * @param string $wiki
	 *
	 * @return bool
	 */
	private function isForeignWiki( $wiki ) {
		return $wiki !== false && $wiki !== wfWikiID();
	}

	/**
	 * @param int $i
	 * @param array $groups
	 * @param bool|string $wiki
	 *
	 * @return DatabaseBase
	 * @throws DBAccessError
	 * @throws MWException
	 */
	public function getConnection( $i, $groups = [], $wiki = false ) {
		if ( $this->isCloaked() && $this->isForeignWiki( $wiki ) ) {
			throw new DBAccessError( 'Cannot access foreign databases when cloaked: ' . $wiki );
		}

		return parent::getConnection( $i, $groups, $wiki );
	}

	/**
	 * @param array $server
	 * @param bool $dbNameOverride
	 * @throws MWException
	 * @return DatabaseBase
	 */
	protected function reallyOpenConnection( $server, $dbNameOverride = false ) {
		if ( $this->isCloaked() && $dbNameOverride !== false ) {
			throw new DBAccessError( 'Cannot use DB name override when cloaked: ' . $wiki );
		}

		$connection = parent::reallyOpenConnection( $server );

		if ( $this->isCloaked() ) {
			$this->cloakConnection( $connection );
		}

		return $connection;
	}
}
