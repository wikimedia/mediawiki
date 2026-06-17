<?php

namespace Wikimedia\LockManager;

use InvalidArgumentException;
use LogicException;
use StatusValue;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * A simplified Database Lock Manager.
 */
class DatabaseLockManager extends LockManager {

	/** @var IConnectionProvider */
	private IConnectionProvider $dbProvider;

	/**
	 * Construct a new instance from configuration.
	 * * @param array $config
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		// Inject the connection provider
		$this->dbProvider = $config['dbProvider'];
	}

	/**
	 * @inheritDoc
	 */
	public function lockByType( array $pathsByType, $timeout = 0 ) {
		$status = StatusValue::newGood();
		$pathsByType = $this->normalizePathsByType( $pathsByType );

		foreach ( $pathsByType as $type => $keys ) {
			if ( $type == self::LOCK_UW ) {
				throw new InvalidArgumentException(
					'DatabaseLockManager does not support LOCK_UW'
				);
			}
			if ( $type == self::LOCK_SH ) {
				// LOCK_SH is no-op
				continue;
			}
			foreach ( $keys as $key ) {
				$res = $this->dbProvider->getPrimaryDatabase()->lock(
					$this->sha1Base36Absolute( $key ),
					// It's not really __METHOD__ but maybe better than nothing specially
					// given the fact that the locked key is a hash
					substr( "{$this->domain}:{$key}", 0, 15 ),
					$timeout
				);

				if ( !$res ) {
					// If one lock fails, fail the batch.
					$status->fatal( 'lockmanager-fail-acquirelock', $key );
				}
			}
		}

		return $status;
	}

	/**
	 * @inheritDoc
	 */
	protected function doUnlockByType( array $pathsByType ) {
		$status = StatusValue::newGood();
		foreach ( $pathsByType as $type => $keys ) {
			if ( $type == self::LOCK_UW ) {
				throw new InvalidArgumentException(
					'DatabaseLockManager does not support LOCK_UW'
				);
			}
			if ( $type == self::LOCK_SH ) {
				// LOCK_SH is no-op
				continue;
			}
			foreach ( $keys as $key ) {
				$res = $this->dbProvider->getPrimaryDatabase()->unlock(
					$this->sha1Base36Absolute( $key ),
					// It's not really __METHOD__ but maybe better than nothing specially
					// given the fact that the locked key is a hash
					substr( "{$this->domain}:{$key}", 0, 15 )
				);

				if ( !$res ) {
					// If one lock fails, fail the batch.
					$status->fatal( 'lockmanager-fail-unlock', $key );
				}
			}
		}

		return $status;
	}

	/**
	 * Since we are overriding ::lockByType, this should never be reached
	 *
	 * @return never
	 */
	protected function doLockByType( array $pathsByType ) {
		throw new LogicException(
			'DatabaseLockManager does not implement ::doLockByType'
		);
	}
}
