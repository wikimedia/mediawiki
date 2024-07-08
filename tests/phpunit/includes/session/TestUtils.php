<?php

namespace MediaWiki\Session;

use PHPUnit\Framework\Assert;
use Psr\Log\LoggerInterface;
use Wikimedia\TestingAccessWrapper;

/**
 * Utility functions for Session unit tests
 */
class TestUtils {

	/**
	 * Override the singleton for unit testing
	 * @param SessionManager|null $manager
	 * @return \\Wikimedia\ScopedCallback|null
	 */
	public static function setSessionManagerSingleton( SessionManager $manager = null ) {
		session_write_close();

		$staticAccess = TestingAccessWrapper::newFromClass( SessionManager::class );

		$oldInstance = $staticAccess->instance;

		$reset = [
			[ 'instance', $oldInstance ],
			[ 'globalSession', $staticAccess->globalSession ],
			[ 'globalSessionRequest', $staticAccess->globalSessionRequest ],
		];

		$staticAccess->instance = $manager;
		$staticAccess->globalSession = null;
		$staticAccess->globalSessionRequest = null;
		if ( $manager && PHPSessionHandler::isInstalled() ) {
			PHPSessionHandler::install( $manager );
		}

		return new \Wikimedia\ScopedCallback( static function () use ( $reset, $staticAccess, $oldInstance ) {
			foreach ( $reset as [ $property, $oldValue ] ) {
				$staticAccess->$property = $oldValue;
			}
			if ( $oldInstance && PHPSessionHandler::isInstalled() ) {
				PHPSessionHandler::install( $oldInstance );
			}
		} );
	}

	/**
	 * If you need a SessionBackend for testing but don't want to create a real
	 * one, use this.
	 * @return SessionBackend Unconfigured! Use reflection to set any private
	 *  fields necessary.
	 */
	public static function getDummySessionBackend() {
		$rc = new \ReflectionClass( SessionBackend::class );
		if ( !method_exists( $rc, 'newInstanceWithoutConstructor' ) ) {
			Assert::markTestSkipped(
				'ReflectionClass::newInstanceWithoutConstructor isn\'t available'
			);
		}

		$ret = $rc->newInstanceWithoutConstructor();
		TestingAccessWrapper::newFromObject( $ret )->logger = new \TestLogger;
		return $ret;
	}

	/**
	 * If you need a Session for testing but don't want to create a backend to
	 * construct one, use this.
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param object|null $backend Object to serve as the SessionBackend
	 * @param int $index
	 * @param LoggerInterface|null $logger
	 * @return Session
	 */
	public static function getDummySession( $backend = null, $index = -1, $logger = null ) {
		$rc = new \ReflectionClass( Session::class );

		if ( $backend === null ) {
			$backend = new DummySessionBackend;
		}

		$session = $rc->newInstanceWithoutConstructor();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->backend = $backend;
		$priv->index = $index;
		$priv->logger = $logger ?: new \TestLogger;
		return $session;
	}

}
