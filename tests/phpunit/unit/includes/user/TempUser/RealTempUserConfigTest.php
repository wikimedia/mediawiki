<?php

namespace MediaWiki\Tests\Unit\User\TempUser;

use BadMethodCallException;
use MediaWiki\Tests\MockDatabase;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\IExpression;
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
			'getMatchPatterns' => [ 'getMatchPatterns' ],
			'getGeneratorPattern' => [ 'getGeneratorPattern' ],
		];
	}

	public function testGetMatchConditionThrowsWhenTempUsersAreDisabled() {
		$this->expectException( BadMethodCallException::class );
		$objectUnderTest = $this->getMockBuilder( RealTempUserConfig::class )
			->onlyMethods( [ 'isEnabled' ] )
			->disableOriginalConstructor()
			->getMock();
		// Simulate that the AutoCreateTempUser config has 'enabled' as false.
		$objectUnderTest->method( 'isEnabled' )
			->willReturn( false );
		$objectUnderTest->getMatchCondition( new MockDatabase, 'foo', IExpression::LIKE );
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

	public function testIsKnownWhenEnabledIsTrue() {
		$realTempUserConfig = new RealTempUserConfig( [
			'enabled' => true,
			'actions' => [ 'edit' ],
			'genPattern' => '',
			'serialProvider' => '',
			'serialMapping' => '',
			'known' => false,
		] );
		$this->assertTrue( $realTempUserConfig->isKnown() );
	}

	public function testIsTempNameWhenKnownIsTrueAndEnabledIsFalse() {
		$realTempUserConfig = new RealTempUserConfig( [
			'enabled' => false,
			'known' => true,
			'actions' => [ 'edit' ],
			'genPattern' => '~$1',
			'serialProvider' => '',
			'serialMapping' => '',
		] );
		$this->assertTrue( $realTempUserConfig->isTempName( '~2024-foo' ) );
	}

	public function testIsTempNameWhenKnownIsFalseAndEnabledIsFalse() {
		$realTempUserConfig = new RealTempUserConfig( [
			'enabled' => false,
			'known' => false,
			'actions' => [ 'edit' ],
			'genPattern' => '',
			'serialProvider' => '',
			'serialMapping' => '',
			'matchPattern' => [ '~$1' ],
		] );
		$this->assertFalse( $realTempUserConfig->isTempName( '~2024-foo' ) );
	}
}
