<?php

/**
 * @author Addshore
 *
 * @since 1.27
 */
class WatchedItemStore {

	/**
	 * @var IDatabase
	 */
	private $masterDb;

	/**
	 * @var IDatabase
	 */
	private $slaveDb;

	/**
	 * @var Config
	 */
	private $config;

	public function __construct( IDatabase $masterDb, IDatabase $slaveDb, Config $config ) {
		$this->masterDb = $masterDb;
		$this->slaveDb = $slaveDb;
		$this->config = $config;
	}

	public static function newFromGlobalState() {
		return new self(
			wfGetDB( DB_MASTER, 'watchlist' ),
			wfGetDB( DB_SLAVE, 'watchlist' ),
			RequestContext::getMain()->getConfig()
		);
	}

	/**
	 * @param TitleValue $titleValue
	 *
	 * @return int
	 */
	public function countWatchers( TitleValue $titleValue ) {
		return (int)$this->slaveDb->selectField(
			'watchlist',
			'COUNT(*)',
			array(
				'wl_namespace' => $titleValue->getNamespace(),
				'wl_title' => $titleValue->getDBkey(),
			),
			__METHOD__
		);
	}

	/**
	 * @param TitleValue[]|Title[] $titleValues
	 * @param bool $unwatchedPages show unwatched pages obeying UnwatchedPageThreshold setting
	 *
	 * @return array multi dimensional like $return[$namespaceId][$titleString] = $watchers
	 */
	public function countWatchersMultiple( array $titleValues, $unwatchedPages = false ) {
		$unwatchedPageThreshold = $this->config->get( 'UnwatchedPageThreshold' );

		if ( !$unwatchedPages && !is_int( $unwatchedPageThreshold ) ) {
			// TODO throw exception?
			return array();
		}

		$options = array( 'GROUP BY' => array( 'wl_namespace', 'wl_title' ) );
		if ( !$unwatchedPages ) {
			$options['HAVING'] = "COUNT(*) >= $unwatchedPageThreshold";
		}

		$lb = new LinkBatch( $titleValues );
		$res = $this->slaveDb->select(
			'watchlist',
			array( 'wl_title', 'wl_namespace', 'count' => 'COUNT(*)' ),
			array( $lb->constructSet( 'wl', $this->slaveDb ) ),
			__METHOD__,
			$options
		);

		$watchCounts = array();
		foreach ( $res as $row ) {
			$watchCounts[$row->wl_namespace][$row->wl_title] = (int)$row->count;
		}

		return $watchCounts;
	}

	/**
	 * Check if the given title already is watched by the user, and if so
	 * add watches on a new title. To be used for page renames and such.
	 *
	 * @param TitleValue $oldTitle Page title to duplicate entries from, if present
	 * @param TitleValue $newTitle Page title to add watches on
	 */
	public function duplicateEntries( TitleValue $oldTitle, TitleValue $newTitle ) {
		$oldNamespace = $oldTitle->getNamespace();
		if ( MWNamespace::isSubject( $oldNamespace ) ) {
			$otherNs = MWNamespace::getTalk( $oldNamespace );
		} else {
			$otherNs = MWNamespace::getSubject( $oldNamespace );
		}

		$this->doDuplicateEntries( $oldTitle, $newTitle );
		$this->doDuplicateEntries(
			new TitleValue( $otherNs, $oldTitle->getDBkey(), $oldTitle->getFragment() ),
			new TitleValue( $otherNs, $newTitle->getDBkey(), $newTitle->getFragment() )
		);
	}

	/**
	 * Handle duplicate entries. Backend for duplicateEntries().
	 *
	 * @param TitleValue $oldTitle
	 * @param TitleValue $newTitle
	 *
	 * @return bool
	 */
	private function doDuplicateEntries( TitleValue $oldTitle, TitleValue $newTitle ) {
		$oldNamespace = $oldTitle->getNamespace();
		$newNamespace = $newTitle->getNamespace();
		$oldDBkey = $oldTitle->getDBkey();
		$newDBkey = $newTitle->getDBkey();

		$result = $this->masterDb->select(
			'watchlist',
			array( 'wl_user', 'wl_notificationtimestamp' ),
			array( 'wl_namespace' => $oldNamespace, 'wl_title' => $oldDBkey ),
			__METHOD__,
			'FOR UPDATE'
		);

		# Construct array to replace into the watchlist
		$values = array();
		foreach ( $result as $row ) {
			$values[] = array(
				'wl_user' => $row->wl_user,
				'wl_namespace' => $newNamespace,
				'wl_title' => $newDBkey,
				'wl_notificationtimestamp' => $row->wl_notificationtimestamp,
			);
		}

		if ( !empty( $values ) ) {
			# Perform replace
			# Note that multi-row replace is very efficient for MySQL but may be inefficient for
			# some other DBMSes, mostly due to poor simulation by us
			$this->masterDb->replace(
				'watchlist',
				array( array( 'wl_user', 'wl_namespace', 'wl_title' ) ),
				$values,
				__METHOD__
			);
		}
	}

}
