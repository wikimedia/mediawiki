<?php
/*
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\User;

use MediaWiki\User\RestrictedUserGroupCheckerFactory;
use MediaWiki\User\RestrictedUserGroupConfigReader;
use MediaWiki\User\UserGroupRestrictions;
use MediaWiki\User\UserRequirementsConditionChecker;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\RestrictedUserGroupCheckerFactory
 */
class RestrictedUserGroupCheckerFactoryTest extends MediaWikiUnitTestCase {

	private function createFactory(): RestrictedUserGroupCheckerFactory {
		$configReader = $this->createMock( RestrictedUserGroupConfigReader::class );
		$configReader->method( 'getConfig' )
			->willReturn( [
				'interface-admin' => new UserGroupRestrictions( [] ),
			] );
		$conditionChecker = $this->createStub( UserRequirementsConditionChecker::class );

		return new RestrictedUserGroupCheckerFactory( $configReader, $conditionChecker );
	}

	public function testCheckerIsCached() {
		$factory = $this->createFactory();
		$checker1 = $factory->getRestrictedUserGroupChecker();
		$checker2 = $factory->getRestrictedUserGroupChecker();

		$this->assertSame( $checker1, $checker2 );
	}

	public function testCheckerIsCachedPerWiki() {
		$factory = $this->createFactory();
		$checker1 = $factory->getRestrictedUserGroupChecker( 'wiki1' );
		$checker2 = $factory->getRestrictedUserGroupChecker( 'wiki2' );

		$this->assertNotSame( $checker1, $checker2 );
	}
}
