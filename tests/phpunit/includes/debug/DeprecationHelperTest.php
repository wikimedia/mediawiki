<?php

use MediaWiki\MainConfigNames;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Debug\DeprecationHelper
 */
class DeprecationHelperTest extends MediaWikiIntegrationTestCase {

	/** @var TestDeprecatedClass */
	private $testClass;

	/** @var TestDeprecatedSubclass */
	private $testSubclass;

	protected function setUp(): void {
		parent::setUp();
		$this->testClass = new TestDeprecatedClass();
		$this->testSubclass = new TestDeprecatedSubclass();
		$this->overrideConfigValue( MainConfigNames::DevelopmentWarnings, false );
	}

	/**
	 * @dataProvider provideGet
	 */
	public function testGet( $propName, $expectError, string $expectedMessage ) {
		if ( $expectError === true ) {
			try {
				$this->testClass->$propName;
			} catch ( Throwable $e ) {
				$this->assertInstanceOf( Error::class, $e );
				$this->assertSame( $expectedMessage, $e->getMessage() );
				return;
			}
			$this->fail( 'Expected an error' );
		} elseif ( $expectError ) {
			$this->assertErrorTriggered( function () use ( $propName ) {
				$this->assertSame( null, $this->testClass->$propName );
			}, $expectError, $expectedMessage );
		} else {
			$this->assertDeprecationWarningIssued( function () use ( $propName ) {
				$expectedValue = $propName === 'fallbackDeprecatedMethodName' ? 'FOO' : 1;
				$this->assertSame( $expectedValue, $this->testClass->$propName );
			}, $expectedMessage );
		}
	}

	public static function provideGet() {
		return [
			[ 'protectedDeprecated', null,
				'Use of TestDeprecatedClass::$protectedDeprecated was deprecated in MediaWiki 1.23. ' .
					'[Called from DeprecationHelperTest::{closure' ],
			[ 'privateDeprecated', null,
				'Use of TestDeprecatedClass::$privateDeprecated was deprecated in MediaWiki 1.24. ' .
					'[Called from DeprecationHelperTest::{closure' ],
			[ 'fallbackDeprecated', null,
				'Use of TestDeprecatedClass::$fallbackDeprecated was deprecated in MediaWiki 1.25. ' .
					'[Called from DeprecationHelperTest::{closure' ],
			[ 'fallbackDeprecatedMethodName', null,
				'Use of TestDeprecatedClass::$fallbackDeprecatedMethodName was deprecated in MediaWiki 1.26. ' .
					'[Called from DeprecationHelperTest::{closure' ],
			[ 'fallbackGetterOnly', null,
				'Use of TestDeprecatedClass::$fallbackGetterOnly was deprecated in MediaWiki 1.25. ' .
					'[Called from DeprecationHelperTest::{closure' ],
			[ 'protectedNonDeprecated', true,
				'Cannot access non-public property TestDeprecatedClass::$protectedNonDeprecated' ],
			[ 'privateNonDeprecated', true,
				'Cannot access non-public property TestDeprecatedClass::$privateNonDeprecated' ],
			[ 'nonExistent', E_USER_NOTICE, 'Undefined property: TestDeprecatedClass::$nonExistent' ],
		];
	}

	public function testDeprecateDynamicPropertyAccess() {
		$testObject = new class extends TestDeprecatedClass {
			public function __construct() {
				parent::__construct();
				$this->deprecateDynamicPropertiesAccess( '1.23' );
			}
		};
		$this->assertDeprecationWarningIssued(
			static function () use ( $testObject ) {
				$testObject->dynamic_property = 'bla';
			},
			'Use of TestDeprecatedClass::$dynamic_property was deprecated in MediaWiki 1.23. ' .
				'[Called from DeprecationHelperTest::{closure'
		);
	}

	public function testDynamicPropertyNullCoalesce() {
		$testObject = new TestDeprecatedClass();
		$this->assertSame( 'bla', $testObject->dynamic_property ?? 'bla' );
	}

	public function testDynamicPropertyNullCoalesceDeprecated() {
		$testObject = new class extends TestDeprecatedClass {
			public function __construct() {
				parent::__construct();
				$this->deprecateDynamicPropertiesAccess( '1.23' );
			}
		};
		$this->assertDeprecationWarningIssued(
			function () use ( $testObject ) {
				$this->assertSame( 'bla', $testObject->dynamic_property ?? 'bla' );
			},
			'Use of TestDeprecatedClass::$dynamic_property was deprecated in MediaWiki 1.23. ' .
				'[Called from DeprecationHelperTest::{closure'
		);
	}

	public function testDynamicPropertyOnMockObject() {
		$testObject = $this->getMockBuilder( TestDeprecatedClass::class )
			->enableProxyingToOriginalMethods()
			->getMock();
		$testObject->blabla = 'test';
		$this->assertSame( 'test', $testObject->blabla );
	}

	/**
	 * @dataProvider provideSet
	 */
	public function testSet( $propName, $expectedError, $expectedMessage, $expectedValue = 0 ) {
		$this->assertPropertySame( 1, $this->testClass, $propName );
		if ( $expectedError === true ) {
			// Test the hard error, not the deprecation warning in front of it, tested elsewhere
			$this->hideDeprecated( 'TestDeprecatedClass::$fallbackGetterOnly' );
			try {
				$this->testClass->$propName = 0;
			} catch ( Throwable $e ) {
				$this->assertSame( $expectedMessage, $e->getMessage() );
				$this->assertInstanceOf( Error::class, $e );
				return;
			}
			$this->fail( 'Expected an error' );
		} else {
			if ( $propName === 'nonExistent' ) {
				$this->testClass->$propName = 0;
			} else {
				$this->assertDeprecationWarningIssued( function () use ( $propName ) {
					$this->testClass->$propName = 0;
				}, $expectedMessage );
			}
			$this->assertPropertySame( 0, $this->testClass, $propName );
		}

		$this->assertPropertySame( $expectedValue, $this->testClass, $propName );
	}

	public static function provideSet() {
		return [
			[ 'protectedDeprecated', null,
				'Use of TestDeprecatedClass::$protectedDeprecated was deprecated in MediaWiki 1.23. ' .
					'[Called from DeprecationHelperTest::{closure' ],
			[ 'privateDeprecated', null,
				'Use of TestDeprecatedClass::$privateDeprecated was deprecated in MediaWiki 1.24. ' .
					'[Called from DeprecationHelperTest::{closure' ],
			[ 'fallbackDeprecated', null,
				'Use of TestDeprecatedClass::$fallbackDeprecated was deprecated in MediaWiki 1.25. ' .
					'[Called from DeprecationHelperTest::{closure' ],
			[ 'fallbackDeprecatedMethodName', null,
				'Use of TestDeprecatedClass::$fallbackDeprecatedMethodName was deprecated in MediaWiki 1.26. ' .
					'[Called from DeprecationHelperTest::{closure' ],
			[ 'fallbackGetterOnly', true,
				'Cannot access non-public property TestDeprecatedClass::$fallbackGetterOnly' ],
			[ 'protectedNonDeprecated', true,
				'Cannot access non-public property TestDeprecatedClass::$protectedNonDeprecated', 1 ],
			[ 'privateNonDeprecated', true,
				'Cannot access non-public property TestDeprecatedClass::$privateNonDeprecated', 1 ],
			[ 'nonExistent', null,
				'Use of TestDeprecatedClass::$nonExistent was deprecated in MediaWiki 1.23. ' .
					'[Called from DeprecationHelperTest::{closure' ],
		];
	}

	public function testInternalGet() {
		$this->assertSame( [
			'prod' => 1,
			'prond' => 1,
			'prid' => 1,
			'prind' => 1,
		], $this->testClass->getThings() );
	}

	public function testInternalSet() {
		$this->testClass->setThings( 2, 2, 2, 2 );
		$wrapper = TestingAccessWrapper::newFromObject( $this->testClass );
		$this->assertSame( 2, $wrapper->protectedDeprecated );
		$this->assertSame( 2, $wrapper->protectedNonDeprecated );
		$this->assertSame( 2, $wrapper->privateDeprecated );
		$this->assertSame( 2, $wrapper->privateNonDeprecated );
	}

	public function testSubclassGetSet() {
		$fullName = 'TestDeprecatedClass::$privateNonDeprecated';
		$this->assertErrorCaught( function () {
			$this->assertSame( null, $this->testSubclass->getNondeprecatedPrivateParentProperty() );
		}, Error::class, "Cannot access non-public property $fullName" );
		$this->assertErrorCaught( function () {
			$this->testSubclass->setNondeprecatedPrivateParentProperty( 0 );
			$wrapper = TestingAccessWrapper::newFromObject( $this->testSubclass );
			$this->assertSame( 1, $wrapper->privateNonDeprecated );
		}, Error::class, "Cannot access non-public property $fullName" );

		$fullName = 'TestDeprecatedSubclass::$subclassPrivateNondeprecated';
		$this->assertErrorCaught( function () {
			$this->assertSame( null, $this->testSubclass->subclassPrivateNondeprecated );
		}, Error::class, "Cannot access non-public property $fullName" );
		$this->assertErrorCaught( function () {
			$this->testSubclass->subclassPrivateNondeprecated = 0;
			$wrapper = TestingAccessWrapper::newFromObject( $this->testSubclass );
			$this->assertSame( 1, $wrapper->subclassPrivateNondeprecated );
		}, Error::class, "Cannot access non-public property $fullName" );
	}

	protected function assertErrorCaught( callable $callback, $name, $message ) {
		$actualClass = $actualMessage = null;
		try {
			$callback();
		} catch ( Throwable $e ) {
			$actualClass = get_class( $e );
			$actualMessage = $e->getMessage();
		}
		$this->assertSame( $name, $actualClass );
		$this->assertSame( $message, $actualMessage );
	}

	protected function assertErrorTriggered( callable $callback, $level, $message ) {
		$actualLevel = $actualMessage = null;
		set_error_handler( static function ( $errorCode, $errorStr ) use ( &$actualLevel, &$actualMessage ) {
			$actualLevel = $errorCode;
			$actualMessage = $errorStr;
		} );
		$callback();
		restore_error_handler();
		$this->assertSame( $level, $actualLevel );
		$this->assertSame( $message, $actualMessage );
	}

	protected function assertPropertySame( $expected, $object, $propName ) {
		try {
			$this->assertSame( $expected, TestingAccessWrapper::newFromObject( $object )->$propName );
		} catch ( ReflectionException $e ) {
			if ( !preg_match( "/Property (TestDeprecated(Class|Subclass)::\\$?)?$propName does not exist/",
				$e->getMessage() )
			) {
				throw $e;
			}
			// property_exists accepts monkey-patching, Reflection / TestingAccessWrapper doesn't
			if ( property_exists( $object, $propName ) ) {
				$this->assertSame( $expected, $object->$propName );
			}
		}
	}

	protected function assertDeprecationWarningIssued( callable $callback, string $expectedMessage ) {
		$this->expectDeprecationAndContinue( '/' . preg_quote( $expectedMessage, '/' ) . '/' );
		$callback();
	}

	/**
	 * Test bad MW version values to throw exceptions as expected
	 *
	 * @dataProvider provideBadMWVersion
	 */
	public function testBadMWVersion( $version, $expected ) {
		$this->expectException( $expected );

		wfDeprecated( __METHOD__, $version );
	}

	public static function provideBadMWVersion() {
		return [
			[ 1, Exception::class ],
			[ 1.33, Exception::class ],
			[ true, Exception::class ],
			[ null, Exception::class ]
		];
	}
}
