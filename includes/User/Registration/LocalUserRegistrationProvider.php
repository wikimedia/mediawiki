<?php

namespace MediaWiki\User\Registration;

use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IConnectionProvider;

class LocalUserRegistrationProvider implements IUserRegistrationProvider {

	public const TYPE = 'local';

	public function __construct(
		private readonly IConnectionProvider $connectionProvider
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function fetchRegistration( UserIdentity $user ) {
		$id = $user->getId();

		if ( $id === 0 ) {
			return false;
		}

		$userRegistration = $this->connectionProvider->getReplicaDatabase()
				->newSelectQueryBuilder()
				->select( 'user_registration' )
				->from( 'user' )
				->where( [ 'user_id' => $id ] )
				->caller( __METHOD__ )
				->fetchField();

		return wfTimestampOrNull( TS_MW, $userRegistration );
	}

	/**
	 * @inheritDoc
	 */
	public function fetchRegistrationBatch( iterable $users ): array {
		$timestampsById = [];

		foreach ( $users as $user ) {
			// Make the list of user IDs unique.
			$timestampsById[$user->getId()] = null;
		}

		$batches = array_chunk( array_keys( $timestampsById ), 1_000 );

		$dbr = $this->connectionProvider->getReplicaDatabase();

		foreach ( $batches as $userIdBatch ) {
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'user_id', 'user_registration' ] )
				->from( 'user' )
				->where( [ 'user_id' => $userIdBatch ] )
				->caller( __METHOD__ )
				->fetchResultSet();

			foreach ( $res as $row ) {
				$timestampsById[$row->user_id] = wfTimestampOrNull( TS_MW, $row->user_registration );
			}
		}

		return $timestampsById;
	}
}
