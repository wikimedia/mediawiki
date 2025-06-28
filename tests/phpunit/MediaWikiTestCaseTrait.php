<?php

use MediaWiki\Debug\MWDebug;
use MediaWiki\DomainEvent\DomainEventDispatcher;
use MediaWiki\DomainEvent\EventDispatchEngine;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWiki\Message\Message;
use MediaWiki\Tests\Unit\FakeQqxMessageLocalizer;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use SebastianBergmann\Comparator\ComparisonFailure;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Services\NoSuchServiceException;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * For code common to both MediaWikiUnitTestCase and MediaWikiIntegrationTestCase.
 */
trait MediaWikiTestCaseTrait {
	/** @var int|null */
	private $originalPhpErrorFilter;

	/** @var array */
	private $expectedDeprecations = [];

	/** @var array */
	private $actualDeprecations = [];

	/**
	 * Returns a PHPUnit constraint that matches (with `===`) anything other than a fixed set of values.
	 * This can be used to list accepted values, e.g.
	 *   $mock->expects( $this->never() )->method( $this->anythingBut( 'foo', 'bar' ) );
	 * which will throw if any unexpected method is called.
	 *
	 * @param mixed ...$values Values that are not matched
	 * @return Constraint
	 */
	protected function anythingBut( ...$values ) {
		if ( !in_array( '__destruct', $values, true ) ) {
			// Ensure that __destruct is always included. PHPUnit will fail very hard with no
			// useful output if __destruct ends up being called (T280780).
			$values[] = '__destruct';
		}
		return $this->logicalNot( $this->logicalOr(
			...array_map( [ $this, 'identicalTo' ], $values )
		) );
	}

	/**
	 * Return a PHPUnit mock that is expected to never have any methods called on it.
	 *
	 * @psalm-template RealInstanceType of object
	 *
	 * @psalm-param class-string<RealInstanceType> $type
	 * @psalm-param list<string> $allow Methods to allow
	 *
	 * @param string $type
	 * @param string[] $allow Methods to allow
	 *
	 * @return MockObject&RealInstanceType
	 */
	protected function createNoOpMock( $type, $allow = [] ) {
		$mock = $this->createMock( $type );
		$mock->expects( $this->never() )->method( $this->anythingBut( '__debugInfo', '__destruct', ...$allow ) );
		return $mock;
	}

	/**
	 * Return a PHPUnit mock that is expected to never have any methods called on it.
	 *
	 * @psalm-template RealInstanceType of object
	 *
	 * @psalm-param class-string<RealInstanceType> $type
	 * @psalm-param list<string> $allow Methods to allow
	 *
	 * @param string $type
	 * @param string[] $allow methods to allow
	 *
	 * @return MockObject&RealInstanceType
	 */
	protected function createNoOpAbstractMock( $type, $allow = [] ) {
		$mock = $this->getMockBuilder( $type )
			->disableOriginalConstructor()
			->disableOriginalClone()
			->disableArgumentCloning()
			->disallowMockingUnknownTypes()
			->getMockForAbstractClass();
		$mock->expects( $this->never() )->method( $this->anythingBut( '__destruct', ...$allow ) );
		return $mock;
	}

	/**
	 * Create an ObjectFactory with no dependencies and no services
	 *
	 * @return ObjectFactory
	 */
	protected function createSimpleObjectFactory() {
		$serviceContainer = $this->createMock( ContainerInterface::class );
		$serviceContainer->method( 'has' )->willReturn( false );
		$serviceContainer->method( 'get' )->willReturnCallback(
			static function ( $serviceName ) {
				throw new NoSuchServiceException( $serviceName );
			}
		);
		return new ObjectFactory( $serviceContainer );
	}

	/**
	 * Create an initially empty HookContainer with an empty service container
	 * attached. Register only the hooks specified in the parameter.
	 *
	 * @param callable[] $hooks
	 * @return HookContainer
	 */
	protected function createHookContainer( $hooks = [] ) {
		$hookContainer = new HookContainer(
			new StaticHookRegistry(),
			$this->createSimpleObjectFactory()
		);
		foreach ( $hooks as $name => $callback ) {
			$hookContainer->register( $name, $callback );
		}
		return $hookContainer;
	}

	/**
	 * Create an initially empty DoainEventDispatcher with an empty service
	 * container attached. Register only the listeners specified in the parameter.
	 *
	 * @param array<string, callable> $listeners
	 * @return DomainEventDispatcher
	 */
	protected function createEventDispatcher( $listeners = [] ) {
		$eventDispatcher = new EventDispatchEngine(
			$this->createSimpleObjectFactory()
		);
		foreach ( $listeners as $name => $callback ) {
			$eventDispatcher->registerListener( $name, $callback );
		}
		return $eventDispatcher;
	}

	/**
	 * Skip the test if not running the necessary php version
	 *
	 * @since 1.42 (also backported to 1.39.8, 1.40.4 and 1.41.2)
	 *
	 * @param string $op
	 * @param string $version
	 */
	protected static function markTestSkippedIfPhp( $op, $version ) {
		if ( version_compare( PHP_VERSION, $version, $op ) ) {
			self::markTestSkipped( "PHP $version isn't supported for this test" );
		}
	}

	/**
	 * Don't throw a warning if $function is deprecated and called later
	 *
	 * @since 1.19
	 *
	 * @param string $function
	 */
	public function hideDeprecated( $function ) {
		// Construct a regex that will match the message generated by
		// wfDeprecated() if it is called for the specified function.
		$this->filterDeprecated( '/Use of ' . preg_quote( $function, '/' ) . ' /' );
	}

	/**
	 * Don't throw a warning for deprecation messages matching a regex.
	 *
	 * @since 1.35
	 *
	 * @param string $regex
	 */
	public function filterDeprecated( $regex ) {
		MWDebug::filterDeprecationForTest( $regex );
	}

	/**
	 * Expect a deprecation notice, but suppress it and continue operation so we can test that the
	 * deprecated functionality works as intended for compatibility.
	 *
	 * @since 1.39
	 *
	 * @param string $regex Deprecation message that must be triggered.
	 */
	public function expectDeprecationAndContinue( string $regex ): void {
		$this->expectedDeprecations[] = $regex;
		MWDebug::filterDeprecationForTest( $regex, function () use ( $regex ): void {
			$this->actualDeprecations[] = $regex;
		} );
	}

	/**
	 * @postCondition
	 */
	public function expectedDeprecationsPostConditions(): void {
		if ( $this->expectedDeprecations ) {
			$this->assertSame( [],
				array_diff( $this->expectedDeprecations, $this->actualDeprecations ),
				'Expected deprecation warning(s) were not emitted' );
		}
	}

	/**
	 * Check whether file contains given data.
	 * @param string $fileName
	 * @param string $actualData
	 * @param bool $createIfMissing If true, and file does not exist, create it with given data
	 *                              and skip the test.
	 * @param string $msg
	 * @since 1.30
	 */
	protected function assertFileContains(
		$fileName,
		$actualData,
		$createIfMissing = false,
		$msg = ''
	) {
		if ( $createIfMissing ) {
			if ( !is_file( $fileName ) ) {
				file_put_contents( $fileName, $actualData );
				$this->markTestSkipped( "Data file $fileName does not exist" );
			}
		} else {
			$this->assertFileExists( $fileName );
		}
		$this->assertEquals( file_get_contents( $fileName ), $actualData, $msg );
	}

	/**
	 * Assert that an array contains a given expected array.
	 *
	 * Values are compared with strict equality.
	 *
	 * When comparing two flat lists, the actual array must contain at least
	 * each value in the expected array. The order of the values does not matter.
	 *
	 * When comparing associative arrays, multi-dimensional arrays, or nested
	 * lists, then the actual array must contain at least each of the expected
	 * key-value pairs. In this case keys must match exactly, including the
	 * index of values in nested lists. The internal index order of associative
	 * array keys does not matter.
	 *
	 * See also:
	 * - Remove assertArraySubset.
	 *   https://github.com/sebastianbergmann/phpunit/issues/3494
	 *   https://github.com/sebastianbergmann/phpunit/issues/3495
	 * - assertContains lacks actual value. https://github.com/sebastianbergmann/phpunit/issues/3061
	 *
	 * @since 1.41
	 * @param array $expected
	 * @param array $actual
	 * @param string $message
	 */
	protected function assertArrayContains(
		array $expected,
		array $actual,
		$message = ''
	) {
		$isList = array_is_list( $expected ) && array_is_list( $actual );
		$isFlatList = true;

		// Flat list
		if ( $isList ) {
			$reduced = $actual;
			foreach ( $expected as $value ) {
				if ( is_array( $value ) ) {
					// Nested array
					$isFlatList = false;
					break;
				}
				$i = array_search( $value, $reduced, true );
				if ( $i === false ) {
					throw new ExpectationFailedException(
						( $message !== '' ? "$message\n" : '' )
							. sprintf(
								'Failed asserting that %s contains expected %s.',
								var_export( $actual, true ),
								var_export( $expected, true ),
							)
					);
				}
				// Remove matched item from the reduced list, so that if a duplicate
				// is expected, we not mistakenly match the same entry twice.
				array_splice( $reduced, $i, 1 );
			}
		}
		if ( !$isList || !$isFlatList ) {
			// Associative or nested array
			$patched = array_replace_recursive( $actual, $expected );
			ksort( $patched );
			ksort( $actual );
			if ( $actual !== $patched ) {
				throw new ExpectationFailedException(
					( $message !== '' ? "$message\n" : '' )
						. 'Failed asserting that array contains the expected submap.',
					new ComparisonFailure(
						$patched,
						$actual,
						var_export( $patched, true ),
						var_export( $actual, true )
					)
				);
			}
		}
		$this->assertTrue( true, $message );
	}

	/**
	 * Assert that two arrays are equal. By default, this means that both arrays need to hold
	 * the same set of values. Using additional arguments, order and associated key can also
	 * be set as relevant.
	 *
	 * @since 1.20
	 *
	 * @param array $expected
	 * @param array $actual
	 * @param bool $ordered If the order of the values should match
	 * @param bool $named If the keys should match
	 * @param string $message
	 */
	public function assertArrayEquals(
		array $expected, array $actual, $ordered = false, $named = false, string $message = ''
	) {
		if ( !$ordered ) {
			$this->objectAssociativeSort( $expected );
			$this->objectAssociativeSort( $actual );
		}

		if ( !$named ) {
			$expected = array_values( $expected );
			$actual = array_values( $actual );
		}

		$this->assertEquals( $expected, $actual, $message );
	}

	/**
	 * Does an associative sort that works for objects.
	 *
	 * @since 1.20
	 *
	 * @param array &$array
	 */
	protected function objectAssociativeSort( array &$array ) {
		uasort(
			$array,
			static function ( $a, $b ) {
				return serialize( $a ) <=> serialize( $b );
			}
		);
	}

	/**
	 * @before
	 */
	protected function phpErrorFilterSetUp() {
		$this->originalPhpErrorFilter = error_reporting();
	}

	/**
	 * @postCondition
	 */
	protected function phpErrorFilterPostConditions() {
		$phpErrorFilter = error_reporting();

		if ( $phpErrorFilter !== $this->originalPhpErrorFilter ) {
			error_reporting( $this->originalPhpErrorFilter );
			$message = "PHP error_reporting setting found dirty."
				. " Did you forget AtEase::restoreWarnings?";
			$this->fail( $message );
		}
	}

	/**
	 * Re-enable any disabled deprecation warnings and allow same deprecations to be thrown
	 * multiple times in different tests, so the PHPUnit expectDeprecation() works.
	 *
	 * @after
	 */
	protected function mwDebugTearDown() {
		MWDebug::clearLog();
		MWDebug::clearDeprecationFilters();
	}

	/**
	 * Reset any fake timestamps so that they don't mess with any other tests.
	 *
	 * @since 1.37 before that, integration tests had it reset in
	 * MediaWikiIntegrationTestCase::mediaWikiTearDown, and unit tests didn't at all
	 *
	 * @after
	 */
	protected function fakeTimestampTearDown() {
		ConvertibleTimestamp::setFakeTime( null );
	}

	/**
	 * Check that no fake timestamp is set before the tests (e.g. in a test provider).
	 * They should be only set in the tests and cleared by fakeTimestampTearDown().
	 *
	 * @since 1.43
	 * @before
	 */
	protected function fakeTimestampSetUp() {
		$realTime = time();
		$maybeFakeTime = ConvertibleTimestamp::time();
		if ( abs( $maybeFakeTime - $realTime ) > 1 ) {
			$this->assertTrue( false, "Someone set a fake timestamp ($maybeFakeTime) " .
				"and did not clean it up. This will cause confusing test failures." );
		}
	}

	/**
	 * @param string $text
	 * @param array $params
	 * @return Message|MockObject
	 * @since 1.35
	 */
	protected function getMockMessage( string $text = '', array $params = [] ) {
		// Warning, don't use PHPUnit's logicalOr with strings as that's extremely slow!
		$oneOf = fn ( string ...$methods ) => $this->logicalOr(
			...array_map( [ $this, 'identicalTo' ], $methods )
		);

		$msg = $this->createMock( Message::class );
		$msg->method( $oneOf( '__toString', 'escaped', 'getKey', 'parse', 'parseAsBlock',
			'plain', 'text', 'toString' ) )->willReturn( $text );
		$msg->method( 'getParams' )->willReturn( $params );
		$msg->method( $oneOf( 'inContentLanguage', 'inLanguage', 'numParams', 'params',
			'rawParams', 'setContext', 'title', 'useDatabase' ) )->willReturnSelf();
		$msg->method( 'exists' )->willReturn( true );
		return $msg;
	}

	private function failStatus( StatusValue $status, string $reason, string $message = '' ) {
		$reason = $message === '' ? $reason : "$message\n$reason";
		$this->fail( "$reason\n$status" );
	}

	protected function assertStatusOK( StatusValue $status, string $message = '' ) {
		if ( !$status->isOK() ) {
			$errors = $status->splitByErrorType()[0];
			$this->failStatus( $errors, 'Status should be OK', $message );
		} else {
			$this->addToAssertionCount( 1 );
		}
	}

	protected function assertStatusGood( StatusValue $status, string $message = '' ) {
		if ( !$status->isGood() ) {
			$this->failStatus( $status, 'Status should be Good', $message );
		} else {
			$this->addToAssertionCount( 1 );
		}
	}

	protected function assertStatusNotOK( StatusValue $status, string $message = '' ) {
		if ( $status->isOK() ) {
			$this->failStatus( $status, 'Status should not be OK', $message );
		} else {
			$this->addToAssertionCount( 1 );
		}
	}

	protected function assertStatusNotGood( StatusValue $status, string $message = '' ) {
		if ( $status->isGood() ) {
			$this->failStatus( $status, 'Status should not be Good', $message );
		} else {
			$this->addToAssertionCount( 1 );
		}
	}

	protected function assertStatusMessage( string $messageKey, StatusValue $status, string $message = '' ) {
		if ( !$status->hasMessage( $messageKey ) ) {
			$this->failStatus( $status, "Status should have message $messageKey", $message );
		} else {
			$this->addToAssertionCount( 1 );
		}
	}

	/**
	 * Check if the status contains exactly the same messages as the expected status.
	 *
	 * Prefer using assertStatusError / assertStatusWarning unless you really need to check the
	 * parameters, count and order of the messages too.
	 *
	 * This method does not compare isGood() vs isOK() or the values of the statuses, use dedicated
	 * assertion methods for that.
	 *
	 * Note that some differences between the internals of the objects are allowed (such as their own
	 * class, use of MessageSpecifier vs string keys, use of strings vs other scalars for parameters).
	 *
	 * @param StatusValue $expected
	 * @param StatusValue $actual
	 * @param string $message
	 */
	protected function assertStatusMessagesExactly( StatusValue $expected, StatusValue $actual, $message = '' ) {
		$localizer = new FakeQqxMessageLocalizer();

		foreach ( [ 'error', 'warning' ] as $type ) {
			foreach (
				array_map( null, $expected->getMessages( $type ), $actual->getMessages( $type ) )
					as [ $expectedMsg, $actualMsg ]
			) {
				if (
					$expectedMsg === null || $actualMsg === null ||
					$localizer->msg( $expectedMsg )->text() !== $localizer->msg( $actualMsg )->text()
				) {
					$this->failStatus( $actual, "Status messages should be exactly like: $expected\nActual:", $message );
				}
			}
		}

		$this->addToAssertionCount( 1 );
	}

	protected function assertStatusValue( mixed $expected, StatusValue $status, string $message = 'Status value' ) {
		$this->assertEquals( $expected, $status->getValue(), $message );
	}

	protected function assertStatusError( string $messageKey, StatusValue $status, string $message = '' ) {
		$this->assertStatusNotOK( $status, $message );
		$this->assertStatusMessage( $messageKey, $status, $message );
	}

	protected function assertStatusWarning( string $messageKey, StatusValue $status, string $message = '' ) {
		$this->assertStatusNotGood( $status, $message );
		$this->assertStatusOK( $status, $message );
		$this->assertStatusMessage( $messageKey, $status, $message );
	}

	/**
	 * Put each HTML element on its own line and then equals() the results
	 *
	 * Use for nicely formatting of PHPUnit diff output when comparing very
	 * simple HTML
	 *
	 * @since 1.20
	 * @since 1.39 available in MediaWikiUnitTestCase
	 *
	 * @param string $expected HTML on oneline
	 * @param string $actual HTML on oneline
	 * @param string $msg Optional message
	 */
	protected function assertHTMLEquals( $expected, $actual, $msg = '' ) {
		$expected = str_replace( '>', ">\n", $expected );
		$actual = str_replace( '>', ">\n", $actual );

		$this->assertEquals( $expected, $actual, $msg );
	}

	/**
	 * This method allows you to assert that the given callback emits a PHP error. It is a PHPUnit 10 compatible
	 * replacement for expectNotice(), expectWarning(), expectError(), and the other methods deprecated in
	 * https://github.com/sebastianbergmann/phpunit/issues/5062.
	 *
	 * @param int $errorLevel
	 * @param callable $callback
	 * @param string|null $msg String that must be contained in the error message
	 */
	protected function expectPHPError(
		int $errorLevel,
		callable $callback,
		?string $msg = null
	): void {
		try {
			$errorEmitted = false;
			set_error_handler( function ( $_, $actualMsg ) use ( $msg, &$errorEmitted ) {
				if ( $msg !== null ) {
					$this->assertStringContainsString( $msg, $actualMsg );
				}
				$errorEmitted = true;
			}, $errorLevel );
			$callback();
			$this->assertTrue( $errorEmitted, "No PHP error was emitted." );
		} finally {
			restore_error_handler();
		}
	}
}
