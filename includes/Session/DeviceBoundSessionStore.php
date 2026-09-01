<?php

namespace MediaWiki\Session;

use MediaWiki\Utils\MWTimestamp;
use ReportUri\Dbsc\Binding;
use ReportUri\Dbsc\PendingRegistration;
use ReportUri\Dbsc\StoreInterface;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Persistence for report-uri/dbsc-php, keyed by MediaWiki session id.
 *
 * Bindings are rows in device_bound_session under the virtual-dbsc DB
 * domain (local unless mapped in $wgVirtualDomainsMapping, which shares one
 * store across a wiki farm). Pending registrations live in the object stash,
 * separate from bindings per the StoreInterface contract. Corrupt binding
 * rows fail closed: Binding::fromJson() throws and this class lets it
 * propagate rather than degrade a bound session to plain cookie auth.
 *
 * Bindings are scoped to the origin that created them: the table may be shared
 * farm-wide, so a row is only ever valid for its own origin.
 *
 * @since 1.48
 * @ingroup Session
 */
class DeviceBoundSessionStore implements StoreInterface {

	/** Virtual DB domain; map in $wgVirtualDomainsMapping for a shared farm store. */
	public const VIRTUAL_DOMAIN = 'virtual-dbsc';

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param BagOStuff $cache
	 * @param string $origin Scheme and host bindings are scoped to
	 * @param int $bindingTtl Hard re-registration horizon, in seconds
	 * @param int $pendingTtl Registration challenge lifetime, in seconds
	 */
	public function __construct(
		private IConnectionProvider $dbProvider,
		private BagOStuff $cache,
		private string $origin,
		private int $bindingTtl,
		private int $pendingTtl,
	) {
	}

	/** @inheritDoc */
	public function putPendingRegistration( string $sessionId, PendingRegistration $pending ): void {
		$this->cache->set( $this->pendingKey( $sessionId ), $pending->toJson(), $this->pendingTtl );
	}

	/** @inheritDoc */
	public function getPendingRegistration( string $sessionId ): ?PendingRegistration {
		$json = $this->cache->get( $this->pendingKey( $sessionId ) );
		return is_string( $json ) ? PendingRegistration::fromJson( $json ) : null;
	}

	/** @inheritDoc */
	public function deletePendingRegistration( string $sessionId ): void {
		$this->cache->delete( $this->pendingKey( $sessionId ) );
	}

	/** @inheritDoc */
	public function putBinding( string $sessionId, Binding $binding ): void {
		$dbw = $this->dbProvider->getPrimaryDatabase( self::VIRTUAL_DOMAIN );
		$now = (int)MWTimestamp::now( TS_UNIX );
		$dbw->newReplaceQueryBuilder()
			->replaceInto( 'device_bound_session' )
			->uniqueIndexFields( [ 'dbs_origin', 'dbs_session' ] )
			->row( [
				'dbs_session' => $sessionId,
				'dbs_origin' => $this->origin,
				'dbs_subject' => $binding->userId,
				'dbs_binding' => $binding->toJson(),
				'dbs_created' => $dbw->timestamp( $now ),
				'dbs_expires' => $dbw->timestamp( $now + $this->bindingTtl ),
			] )
			->caller( __METHOD__ )
			->execute();
	}

	/** @inheritDoc */
	public function getBinding( string $sessionId ): ?Binding {
		$row = $this->dbProvider->getReplicaDatabase( self::VIRTUAL_DOMAIN )
			->newSelectQueryBuilder()
			->select( [ 'dbs_binding', 'dbs_expires' ] )
			->from( 'device_bound_session' )
			->where( [ 'dbs_origin' => $this->origin, 'dbs_session' => $sessionId ] )
			->caller( __METHOD__ )
			->fetchRow();
		if ( !$row ||
			(int)MWTimestamp::convert( TS_UNIX, $row->dbs_expires ) < (int)MWTimestamp::now( TS_UNIX )
		) {
			return null;
		}
		return Binding::fromJson( $row->dbs_binding );
	}

	/** @inheritDoc */
	public function delete( string $sessionId ): void {
		$this->dbProvider->getPrimaryDatabase( self::VIRTUAL_DOMAIN )
			->newDeleteQueryBuilder()
			->deleteFrom( 'device_bound_session' )
			->where( [ 'dbs_origin' => $this->origin, 'dbs_session' => $sessionId ] )
			->caller( __METHOD__ )
			->execute();
		$this->deletePendingRegistration( $sessionId );
	}

	/**
	 * Drop every binding for a subject (farm-wide sign-out). Not part of
	 * StoreInterface.
	 *
	 * @param string $subject
	 */
	public function terminateAllForSubject( string $subject ): void {
		$this->dbProvider->getPrimaryDatabase( self::VIRTUAL_DOMAIN )
			->newDeleteQueryBuilder()
			->deleteFrom( 'device_bound_session' )
			->where( [ 'dbs_subject' => $subject ] )
			->caller( __METHOD__ )
			->execute();
	}

	private function pendingKey( string $sessionId ): string {
		return $this->cache->makeGlobalKey( 'dbs-pending', $sessionId );
	}
}
