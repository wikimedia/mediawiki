<?php

namespace MediaWiki\Tests\Unit\User\TempUser;

use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\MockServiceDependenciesTrait;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\TempUser\TempUserCreator
 */
class TempUserCreatorTest extends MediaWikiUnitTestCase {

	use MockServiceDependenciesTrait;

	/** @dataProvider providePassThroughMethods */
	public function testPassThroughMethods( $methodName, $mockArguments = [] ) {
		// Tests methods in the TempUserCreator class that pass through to
		// the TempUserConfig methods with the same name.
		$mockTempUserConfig = $this->createMock( RealTempUserConfig::class );
		$mockTempUserConfig->expects( $this->once() )
			->method( $methodName )
			->with( ...$mockArguments );
		$objectUnderTest = $this->newServiceInstance( TempUserCreator::class, [
			'config' => $mockTempUserConfig
		] );
		$objectUnderTest->$methodName( ...$mockArguments );
	}

	public static function providePassThroughMethods() {
		return [
			'isEnabled' => [ 'isEnabled' ],
			'isAutoCreateAction' => [ 'isAutoCreateAction', [ 'mock-action' ] ],
			'isTempName' => [ 'isTempName', [ 'mock-name' ] ],
			'isReservedName' => [ 'isReservedName', [ 'mock-name' ] ],
			'getPlaceholderName' => [ 'getPlaceholderName' ],
			'getMatchPattern' => [ 'getMatchPattern' ],
			'getMatchPatterns' => [ 'getMatchPatterns' ],
			'getExpireAfterDays' => [ 'getExpireAfterDays' ],
			'getNotifyBeforeExpirationDays' => [ 'getNotifyBeforeExpirationDays' ],
		];
	}

	public function testShouldAutoCreate() {
		$this->testPassThroughMethods(
			'shouldAutocreate',
			[ $this->createMock( Authority::class ), 'mock-action' ]
		);
	}
}
