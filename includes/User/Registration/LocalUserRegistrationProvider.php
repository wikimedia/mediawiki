<?php

namespace MediaWiki\User\Registration;

use InvalidArgumentException;
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
		$id = $user->getId( $user->getWikiId() );

		if ( $id === 0 ) {
			return false;
		}

		$userRegistration = $this->connectionProvider->getReplicaDatabase( $user->getWikiId() )
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
		// The output format doesn't allow us to return the wiki ID, so we need to ensure all the input users come
		// from the same wiki. If wikiToQuery is null, it means we haven't set it yet, so we can accept any wiki ID
		// for the first user.
		$wikiToQuery = null;

		foreach ( $users as $user ) {
			$wikiId = $user->getWikiId();

			if ( $wikiToQuery === null ) {
				$wikiToQuery = $wikiId;
			} elseif ( $wikiToQuery !== $wikiId ) {
				throw new InvalidArgumentException( 'All queried users must belong to the same wiki.' );
			}

			// Make the list of user IDs unique.
			$timestampsById[$user->getId( $wikiId )] = null;
		}

		if ( $wikiToQuery === null ) {
			// No users to query.
			return [];
		}

		$batches = array_chunk( array_keys( $timestampsById ), 1_000 );

		$dbr = $this->connectionProvider->getReplicaDatabase( $wikiToQuery );

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
