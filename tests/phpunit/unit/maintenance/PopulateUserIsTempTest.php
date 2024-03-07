<?php

namespace MediaWiki\Tests\Unit\Maintenance;

use MediaWiki\User\TempUser\TempUserConfig;
use MediaWikiUnitTestCase;
use PopulateUserIsTemp;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \PopulateUserIsTemp
 */
class PopulateUserIsTempTest extends MediaWikiUnitTestCase {
	public function testUpdateKey() {
		$objectUnderTest = TestingAccessWrapper::newFromObject( new PopulateUserIsTemp() );
		$this->assertSame(
			PopulateUserIsTemp::class,
			$objectUnderTest->getUpdateKey(),
			'The update key for the PopulateUserTemp class was not as expected.'
		);
	}

	public function testDoDBUpdatesWhenTempAccountsDisabled() {
		// Create a mock TempUserConfig object that will return false for isEnabled and not
		// expect any calls to getMatchPatterns.
		$mockTempUserConfig = $this->createMock( TempUserConfig::class );
		$mockTempUserConfig->method( 'isEnabled' )
			->willReturn( false );
		$mockTempUserConfig->expects( $this->never() )
			->method( 'getMatchCondition' );
		// Get the object under test and set the tempUserConfig property to the mock object.
		$objectUnderTest = $this->getMockBuilder( PopulateUserIsTemp::class )
			->onlyMethods( [ 'initServices' ] )
			->getMock();
		$objectUnderTest = TestingAccessWrapper::newFromObject( $objectUnderTest );
		$objectUnderTest->tempUserConfig = $mockTempUserConfig;
		// Call the doDBUpdates method and assert that it returns true.
		$this->assertTrue(
			$objectUnderTest->doDBUpdates(),
			'The doDBUpdates method did not return true as expected.'
		);
	}
}
