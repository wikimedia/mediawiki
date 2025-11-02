<?php

namespace MediaWiki\User\Options;

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\DBAccessObjectUtils;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;

class LocalUserOptionsStore implements UserOptionsStore {
	private IConnectionProvider $dbProvider;
	private HookRunner $hookRunner;

	/** @var array[] Cached options for each user, by user ID */
	private array $optionsFromDb;

	public function __construct( IConnectionProvider $dbProvider, HookRunner $hookRunner ) {
		$this->dbProvider = $dbProvider;
		$this->hookRunner = $hookRunner;
	}

	public function fetch(
		UserIdentity $user,
		int $recency
	): array {
		// In core, only users with local accounts may have preferences
		if ( !$user->getId() ) {
			return [];
		}

		$dbr = DBAccessObjectUtils::getDBFromRecency( $this->dbProvider, $recency );
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'up_property', 'up_value' ] )
			->from( 'user_properties' )
			->where( [ 'up_user' => $user->getId() ] )
			->recency( $recency )
			->caller( __METHOD__ )->fetchResultSet();

		$options = [];
		foreach ( $res as $row ) {
			$options[$row->up_property] = (string)$row->up_value;
		}

		$this->optionsFromDb[$user->getId()] = $options;
		return $options;
	}

	/** @inheritDoc */
	public function fetchBatchForUserNames( array $keys, array $userNames ) {
		if ( !$keys || !$userNames ) {
			return [];
		}

		$options = [];
		$res = $this->dbProvider->getReplicaDatabase()
			->newSelectQueryBuilder()
			->select( [ 'user_name', 'up_property', 'up_value' ] )
			->from( 'user_properties' )
			->join( 'user', null, 'user_id=up_user' )
			->where( [
				'up_property' => $keys,
				'user_name' => $userNames
			] )
			->caller( __METHOD__ )
			->fetchResultSet();
		foreach ( $res as $row ) {
			$options[$row->up_property][$row->user_name] = (string)$row->up_value;
		}
		return $options;
	}

	/** @inheritDoc */
	public function store( UserIdentity $user, array $updates ) {
		// In core, only users with local accounts may have preferences
		if ( !$user->getId() ) {
			return false;
		}

		$oldOptions = $this->optionsFromDb[ $user->getId() ]
			?? $this->fetch( $user, IDBAccessObject::READ_LATEST );
		$newOptions = $oldOptions;
		$keysToDelete = [];
		$rowsToInsert = [];
		foreach ( $updates as $key => $value ) {
			if ( !UserOptionsManager::isValueEqual(
				$value, $oldOptions[$key] ?? null )
			) {
				// Update by deleting and reinserting
				if ( array_key_exists( $key, $oldOptions ) ) {
					$keysToDelete[] = $key;
					unset( $newOptions[$key] );
				}
				if ( $value !== null ) {
					$truncValue = mb_strcut( $value, 0,
						UserOptionsManager::MAX_BYTES_OPTION_VALUE );
					$rowsToInsert[] = [
						'up_user' => $user->getId(),
						'up_property' => $key,
						'up_value' => $truncValue,
					];
					$newOptions[$key] = $truncValue;
				}
			}
		}
		if ( !count( $keysToDelete ) && !count( $rowsToInsert ) ) {
			// Nothing to do
			return false;
		}

		// Do the DELETE
		$dbw = $this->dbProvider->getPrimaryDatabase();
		if ( $keysToDelete ) {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'user_properties' )
				->where( [ 'up_user' => $user->getId() ] )
				->andWhere( [ 'up_property' => $keysToDelete ] )
				->caller( __METHOD__ )->execute();
		}
		if ( $rowsToInsert ) {
			// Insert the new preference rows
			$dbw->newInsertQueryBuilder()
				->insertInto( 'user_properties' )
				->ignore()
				->rows( $rowsToInsert )
				->caller( __METHOD__ )->execute();
		}

		// Update cache
		$this->optionsFromDb[$user->getId()] = $newOptions;

		$this->hookRunner->onLocalUserOptionsStoreSave( $user, $oldOptions, $newOptions );

		return true;
	}

}
