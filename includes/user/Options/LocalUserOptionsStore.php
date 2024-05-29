<?php

namespace MediaWiki\User\Options;

use DBAccessObjectUtils;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IConnectionProvider;

class LocalUserOptionsStore implements UserOptionsStore {
	private IConnectionProvider $dbProvider;

	/** @var array[] Cached options for each user, by user ID */
	private $optionsFromDb;

	public function __construct( IConnectionProvider $dbProvider ) {
		$this->dbProvider = $dbProvider;
	}

	public function fetch(
		UserIdentity $user,
		int $recency
	): array {
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

	public function store( UserIdentity $user, array $updates ) {
		$oldOptions = $this->optionsFromDb[ $user->getId() ]
			?? $this->fetch( $user, \IDBAccessObject::READ_LATEST );
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

		return true;
	}

}
