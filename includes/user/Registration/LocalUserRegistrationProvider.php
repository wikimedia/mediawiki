<?php

namespace MediaWiki\User\Registration;

use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IConnectionProvider;

class LocalUserRegistrationProvider implements IUserRegistrationProvider {

	public const TYPE = 'local';

	private UserFactory $userFactory;
	private IConnectionProvider $connectionProvider;

	public function __construct(
		UserFactory $userFactory,
		IConnectionProvider $connectionProvider
	) {
		$this->userFactory = $userFactory;
		$this->connectionProvider = $connectionProvider;
	}

	/**
	 * @inheritDoc
	 */
	public function fetchRegistration( UserIdentity $user ) {
		// TODO: Factor this out from User::getRegistration to this method (T352871)
		$user = $this->userFactory->newFromUserIdentity( $user );
		return $user->getRegistration();
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
