<?php

namespace MediaWiki\Tests\Session;

use MediaWiki\Session\Session;
use MediaWiki\Session\SessionBackend;
use PHPUnit\Framework\Assert;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use TestLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * Utility functions for Session unit tests
 */
class TestUtils {

	/**
	 * If you need a SessionBackend for testing but don't want to create a real
	 * one, use this.
	 * @return SessionBackend Unconfigured! Use reflection to set any private
	 *  fields necessary.
	 */
	public static function getDummySessionBackend() {
		$rc = new ReflectionClass( SessionBackend::class );
		if ( !method_exists( $rc, 'newInstanceWithoutConstructor' ) ) {
			Assert::markTestSkipped(
				'ReflectionClass::newInstanceWithoutConstructor isn\'t available'
			);
		}

		$ret = $rc->newInstanceWithoutConstructor();
		TestingAccessWrapper::newFromObject( $ret )->logger = new TestLogger;
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
		$rc = new ReflectionClass( Session::class );

		$session = $rc->newInstanceWithoutConstructor();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->backend = $backend ?? new DummySessionBackend();
		$priv->index = $index;
		$priv->logger = $logger ?? new TestLogger();
		return $session;
	}

}
