<?php

namespace MediaWiki\Installer;

use MediaWiki\Installer\Task\ITaskContext;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Common code for MySQL and PostgreSQL, which are similar due to the fact that
 * databases can be created by getting a connection to a network service.
 *
 * SQLite does not inherit this because its connection handles can't operate on
 * databases other than the one you originally connected to.
 */
abstract class NetworkedDatabaseCreator extends DatabaseCreator {
	/** @inheritDoc */
	public function existsLocally( $database ) {
		$connStatus = $this->context->getConnection( ITaskContext::CONN_CREATE_DATABASE );
		if ( !$connStatus->isOK() ) {
			return false;
		}
		return $this->existsInConnection( $connStatus->getDB(), $database );
	}

	/** @inheritDoc */
	public function existsInLoadBalancer( ILoadBalancer $loadBalancer, $database ) {
		$conn = $loadBalancer->getConnection( DB_PRIMARY, [], DatabaseDomain::newUnspecified()->getId() );
		return $this->existsInConnection( $conn, $database );
	}

	/** @inheritDoc */
	public function createLocally( $database ): Status {
		$connStatus = $this->context->getConnection( ITaskContext::CONN_CREATE_DATABASE );
		if ( !$connStatus->isOK() ) {
			return $connStatus;
		}
		$conn = $connStatus->getDB();
		return $this->createInConnection( $conn, $database );
	}

	/** @inheritDoc */
	public function createInLoadBalancer( ILoadBalancer $loadBalancer, $database ): Status {
		$conn = $loadBalancer->getConnection( DB_PRIMARY, [], DatabaseDomain::newUnspecified()->getId() );
		return $this->createInConnection( $conn, $database );
	}

	/**
	 * Determine whether a database exists on a connection
	 *
	 * @param IDatabase $conn
	 * @param string $database
	 * @return bool
	 */
	abstract protected function existsInConnection( IDatabase $conn, $database );

	/**
	 * Create a database on a connection
	 *
	 * @param IDatabase $conn
	 * @param string $database
	 * @return Status
	 */
	abstract protected function createInConnection( IDatabase $conn, $database ): Status;
}
