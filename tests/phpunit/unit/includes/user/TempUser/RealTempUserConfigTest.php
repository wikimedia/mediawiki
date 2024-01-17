<?php

namespace MediaWiki\Tests\Unit\User\TempUser;

use BadMethodCallException;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWikiUnitTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\User\TempUser\RealTempUserConfig
 */
class RealTempUserConfigTest extends MediaWikiUnitTestCase {

	/** @dataProvider provideMethodsThatThrowWhenTempUsersAreDisabled */
	public function testMethodsThatThrowWhenTempUsersAreDisabled( $methodName ) {
		$this->expectException( BadMethodCallException::class );
		$objectUnderTest = $this->getMockBuilder( RealTempUserConfig::class )
			->onlyMethods( [ 'isEnabled' ] )
			->disableOriginalConstructor()
			->getMock();
		// Simulate that the AutoCreateTempUser config has 'enabled' as false.
		$objectUnderTest->method( 'isEnabled' )
			->willReturn( false );
		$objectUnderTest->$methodName();
	}

	public static function provideMethodsThatThrowWhenTempUsersAreDisabled() {
		return [
			'getPlaceholderName' => [ 'getPlaceholderName' ],
			'getMatchPattern' => [ 'getMatchPattern' ],
			'getMatchPatterns' => [ 'getMatchPatterns' ],
			'getGeneratorPattern' => [ 'getGeneratorPattern' ],
		];
	}

	/** @dataProvider provideIsEnabled */
	public function testIsEnabled( $enabledValue ) {
		// Get the object under test with the constructor disabled.
		$objectUnderTest = $this->getMockBuilder( RealTempUserConfig::class )
			->onlyMethods( [] )
			->disableOriginalConstructor()
			->getMock();
		// Set $objectUnderTest->enabled to $enabledValue
		$objectUnderTest = TestingAccessWrapper::newFromObject( $objectUnderTest );
		$objectUnderTest->enabled = $enabledValue;
		$this->assertSame( $enabledValue, $objectUnderTest->isEnabled() );
	}

	public static function provideIsEnabled() {
		return [
			'Auto creation is enabled' => [ true ],
			'Auto creation is disabled' => [ false ],
		];
	}
}
