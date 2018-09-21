<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers DeprecationHelper
 */
class DeprecationHelperTest extends MediaWikiTestCase {

	/** @var TestDeprecatedClass */
	private $testClass;

	/** @var TestDeprecatedSubclass */
	private $testSubclass;

	public function setUp() {
		parent::setUp();
		$this->testClass = new TestDeprecatedClass();
		$this->testSubclass = new TestDeprecatedSubclass();
		$this->setMwGlobals( 'wgDevelopmentWarnings', false );
	}

	/**
	 * @dataProvider provideGet
	 */
	public function testGet( $propName, $expectedLevel, $expectedMessage ) {
		if ( $expectedLevel ) {
			$this->assertErrorTriggered( function () use ( $propName ) {
				$this->assertSame( null, $this->testClass->$propName );
			}, $expectedLevel, $expectedMessage );
		} else {
			$this->assertDeprecationWarningIssued( function () use ( $propName ) {
				$this->assertSame( 1, $this->testClass->$propName );
			} );
		}
	}

	public function provideGet() {
		return [
			[ 'protectedDeprecated', null, null ],
			[ 'protectedNonDeprecated', E_USER_ERROR,
				'Cannot access non-public property TestDeprecatedClass::$protectedNonDeprecated' ],
			[ 'privateDeprecated', null, null ],
			[ 'privateNonDeprecated', E_USER_ERROR,
			  'Cannot access non-public property TestDeprecatedClass::$privateNonDeprecated' ],
			[ 'nonExistent', E_USER_NOTICE, 'Undefined property: TestDeprecatedClass::$nonExistent' ],
		];
	}

	/**
	 * @dataProvider provideSet
	 */
	public function testSet( $propName, $expectedLevel, $expectedMessage ) {
		$this->assertPropertySame( 1, $this->testClass, $propName );
		if ( $expectedLevel ) {
			$this->assertErrorTriggered( function () use ( $propName ) {
				$this->testClass->$propName = 0;
				$this->assertPropertySame( 1, $this->testClass, $propName );
			}, $expectedLevel, $expectedMessage );
		} else {
			if ( $propName === 'nonExistent' ) {
				$this->testClass->$propName = 0;
			} else {
				$this->assertDeprecationWarningIssued( function () use ( $propName ) {
					$this->testClass->$propName = 0;
				} );
			}
			$this->assertPropertySame( 0, $this->testClass, $propName );
		}
	}

	public function provideSet() {
		return [
			[ 'protectedDeprecated', null, null ],
			[ 'protectedNonDeprecated', E_USER_ERROR,
			  'Cannot access non-public property TestDeprecatedClass::$protectedNonDeprecated' ],
			[ 'privateDeprecated', null, null ],
			[ 'privateNonDeprecated', E_USER_ERROR,
			  'Cannot access non-public property TestDeprecatedClass::$privateNonDeprecated' ],
			[ 'nonExistent', null, null ],
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
		$this->assertDeprecationWarningIssued( function () {
			$this->assertSame( 1, $this->testSubclass->getDeprecatedPrivateParentProperty() );
		} );
		$this->assertDeprecationWarningIssued( function () {
			$this->testSubclass->setDeprecatedPrivateParentProperty( 0 );
		} );
		$wrapper = TestingAccessWrapper::newFromObject( $this->testSubclass );
		$this->assertSame( 0, $wrapper->privateDeprecated );

		$fullName = 'TestDeprecatedClass::$privateNonDeprecated';
		$this->assertErrorTriggered( function () {
			$this->assertSame( null, $this->testSubclass->getNonDeprecatedPrivateParentProperty() );
		}, E_USER_ERROR, "Cannot access non-public property $fullName" );
		$this->assertErrorTriggered( function () {
			$this->testSubclass->setNonDeprecatedPrivateParentProperty( 0 );
			$wrapper = TestingAccessWrapper::newFromObject( $this->testSubclass );
			$this->assertSame( 1, $wrapper->privateNonDeprecated );
		}, E_USER_ERROR, "Cannot access non-public property $fullName" );
	}

	protected function assertErrorTriggered( callable $callback, $level, $message ) {
		$actualLevel = $actualMessage = null;
		set_error_handler( function ( $errorCode, $errorStr ) use ( &$actualLevel, &$actualMessage ) {
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
			if ( !preg_match( "/Property (TestDeprecated(Class|Subclass)::)?$propName does not exist/",
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

	protected function assertDeprecationWarningIssued( callable $callback ) {
		MWDebug::clearLog();
		$callback();
		$wrapper = TestingAccessWrapper::newFromClass( MWDebug::class );
		$this->assertNotEmpty( $wrapper->deprecationWarnings );
	}

}
