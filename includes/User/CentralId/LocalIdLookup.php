<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User\CentralId;

use MediaWiki\Block\HideUserUtils;
use MediaWiki\Config\Config;
use MediaWiki\MainConfigNames;
use MediaWiki\User\UserIdentity;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\DBAccessObjectUtils;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * A CentralIdLookup provider that just uses local IDs. Useful if the wiki
 * isn't part of a cluster or you're using shared user tables.
 *
 * @note Shared user table support expects that all wikis involved have
 *  $wgSharedDB and $wgSharedTables set, and that all wikis involved in the
 *  sharing are listed in $wgLocalDatabases, and that no wikis not involved in
 *  the sharing are listed in $wgLocalDatabases.
 * @since 1.27
 */
class LocalIdLookup extends CentralIdLookup {

	private IConnectionProvider $dbProvider;
	private HideUserUtils $hideUserUtils;

	/** @var string|null */
	private $sharedDB;

	/** @var string[] */
	private $sharedTables;

	/** @var string[] */
	private $localDatabases;

	/**
	 * @param Config $config
	 * @param IConnectionProvider $dbProvider
	 * @param HideUserUtils $hideUserUtils
	 */
	public function __construct(
		Config $config,
		IConnectionProvider $dbProvider,
		HideUserUtils $hideUserUtils
	) {
		$this->sharedDB = $config->get( MainConfigNames::SharedDB );
		$this->sharedTables = $config->get( MainConfigNames::SharedTables );
		$this->localDatabases = $config->get( MainConfigNames::LocalDatabases );
		$this->dbProvider = $dbProvider;
		$this->hideUserUtils = $hideUserUtils;
	}

	/** @inheritDoc */
	public function isAttached( UserIdentity $user, $wikiId = UserIdentity::LOCAL ): bool {
		// If the user has no ID, it can't be attached
		if ( !$user->isRegistered() ) {
			return false;
		}

		return $this->isWikiSharingOurUsers( $wikiId );
	}

	/**
	 * Check if the specified wiki shares a user table with the current wiki
	 *
	 * @param string|false $wikiId
	 * @return bool
	 */
	private function isWikiSharingOurUsers( $wikiId ) {
		// Easy case, the local wiki
		if ( !$wikiId || WikiMap::isCurrentWikiId( $wikiId ) ) {
			return true;
		}

		// Assume that shared user tables are set up as described above, if
		// they're being used at all.
		return $this->sharedDB !== null &&
			in_array( 'user', $this->sharedTables, true ) &&
			in_array( $wikiId, $this->localDatabases, true );
	}

	/** @inheritDoc */
	public function lookupCentralIds(
		array $idToName, $audience = self::AUDIENCE_PUBLIC, $flags = IDBAccessObject::READ_NORMAL
	): array {
		if ( !$idToName ) {
			return [];
		}
		$audience = $this->checkAudience( $audience );
		$db = DBAccessObjectUtils::getDBFromRecency( $this->dbProvider, $flags );
		$queryBuilder = $db->newSelectQueryBuilder();
		$queryBuilder
			->select( [ 'user_id', 'user_name' ] )
			->from( 'user' )
			->where( [ 'user_id' => array_map( 'intval', array_keys( $idToName ) ) ] )
			->recency( $flags );

		if ( $audience && !$audience->isAllowed( 'hideuser' ) ) {
			$this->hideUserUtils->addFieldToBuilder( $queryBuilder );
		}

		$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();
		foreach ( $res as $row ) {
			$idToName[$row->user_id] = empty( $row->hu_deleted ) ? $row->user_name : '';
		}

		return $idToName;
	}

	/** @inheritDoc */
	public function lookupUserNamesWithFilter(
		array $nameToId, $filter, $audience = self::AUDIENCE_PUBLIC,
		$flags = IDBAccessObject::READ_NORMAL,
		$wikiId = UserIdentity::LOCAL
	): array {
		if ( !$nameToId ) {
			return [];
		}
		if ( ( $filter === self::FILTER_ATTACHED || $filter === self::FILTER_OWNED )
			&& !$this->isWikiSharingOurUsers( $wikiId )
		) {
			// No users pass the filter
			return $nameToId;
		}

		$audience = $this->checkAudience( $audience );
		$db = DBAccessObjectUtils::getDBFromRecency( $this->dbProvider, $flags );
		$queryBuilder = $db->newSelectQueryBuilder();
		$queryBuilder
			->select( [ 'user_id', 'user_name' ] )
			->from( 'user' )
			->where( [ 'user_name' => array_map( 'strval', array_keys( $nameToId ) ) ] )
			->recency( $flags );

		if ( $audience && !$audience->isAllowed( 'hideuser' ) ) {
			$queryBuilder->andWhere( $this->hideUserUtils->getExpression( $db ) );
		}

		$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();
		foreach ( $res as $row ) {
			$nameToId[$row->user_name] = (int)$row->user_id;
		}

		return $nameToId;
	}

	/** @inheritDoc */
	public function getScope(): string {
		return $this->getProviderId() . ':'
			. strtr( $this->sharedDB ?? WikiMap::getCurrentWikiId(), [ ':' => '-' ] );
	}

}

/** @deprecated class alias since 1.41 */
class_alias( LocalIdLookup::class, 'LocalIdLookup' );
