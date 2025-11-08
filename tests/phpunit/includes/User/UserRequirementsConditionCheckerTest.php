<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\User;

use InvalidArgumentException;
use MediaWiki\Request\FauxRequest;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\User\UserRequirementsConditionChecker
 * @group Database
 */
class UserRequirementsConditionCheckerTest extends MediaWikiIntegrationTestCase {

	/** @dataProvider provideConditionsRelevantToPerformer */
	public function testConditionsRelevantToPerformer( array $conditions, bool $isPerformer, bool $expected ): void {
		$request = new FauxRequest();
		$request->setIP( '127.0.0.1' );
		$this->setRequest( $request );

		$user = new UserIdentityValue( 1, 'Test User' );
		$registrationLookupMock = $this->createMock( UserRegistrationLookup::class );
		$registrationLookupMock->method( 'getRegistration' )
			->willReturn( '20000101000000' );
		$this->setService( 'UserRegistrationLookup', $registrationLookupMock );

		$checker = $this->getServiceContainer()->getUserRequirementsConditionChecker();
		$result = $checker->recursivelyCheckCondition( $conditions, $user, $isPerformer );
		$this->assertSame( $expected, $result );
	}

	public static function provideConditionsRelevantToPerformer(): array {
		return [
			// Primitive conditions
			'The performer is from valid IP' => [
				'conditions' => [ APCOND_ISIP, '127.0.0.1' ],
				'isPerformer' => true,
				'expected' => true,
			],
			'The performer is from valid IP rage' => [
				'conditions' => [ APCOND_IPINRANGE, '127.0.0.0/8' ],
				'isPerformer' => true,
				'expected' => true,
			],
			'The non-performer is from otherwise valid IP' => [
				'conditions' => [ APCOND_ISIP, '127.0.0.1' ],
				'isPerformer' => false,
				'expected' => false,
			],
			'The non-performer is from otherwise valid IP rage' => [
				'conditions' => [ APCOND_IPINRANGE, '127.0.0.0/8' ],
				'isPerformer' => false,
				'expected' => false,
			],
			// Complex conditions
			'The non-performer is from otherwise valid IP but also has sufficiently old account (OR)' => [
				'conditions' => [ '|', [ APCOND_ISIP, '127.0.0.1' ], [ APCOND_AGE, 60 ] ],
				'isPerformer' => false,
				'expected' => true,
			],
			'The non-performer is from otherwise valid IP but also has sufficiently old account (AND)' => [
				'conditions' => [ '&', [ APCOND_ISIP, '127.0.0.1' ], [ APCOND_AGE, 60 ] ],
				'isPerformer' => false,
				'expected' => false,
			],
		];
	}

	/** @dataProvider provideConditionsNotSupportedForRemoteUsers */
	public function testConditionsNotSupportedForRemoteUsers( array $conditions ): void {
		// Ensure that the user would meet the conditions if they were local
		$ugmManagerMock = $this->createMock( UserGroupManager::class );
		$ugmManagerMock->method( 'getUserGroups' )->willReturn( [ 'bot' ] );
		$this->setService( 'UserGroupManager', $ugmManagerMock );

		$userMock = $this->createMock( User::class );
		$userMock->method( 'getEmail' )->willReturn( 'test@example.com' );
		$userMock->method( 'getEmailAuthenticationTimestamp' )->willReturn( '20250101000000' );

		$userFactoryMock = $this->createMock( UserFactory::class );
		$userFactoryMock->method( 'newFromUserIdentity' )->willReturn( $userMock );
		$this->setService( 'UserFactory', $userFactoryMock );

		$user = new UserIdentityValue( 1, 'Test User', 'otherwiki' );
		$checker = $this->getServiceContainer()->getUserRequirementsConditionChecker();
		$result = $checker->recursivelyCheckCondition( $conditions, $user, false );
		$this->assertFalse( $result );
	}

	public static function provideConditionsNotSupportedForRemoteUsers(): array {
		return [
			'APCOND_EMAILCONFIRMED' => [
				'conditions' => [ APCOND_EMAILCONFIRMED ],
			],
			'APCOND_INGROUPS' => [
				'conditions' => [ APCOND_INGROUPS, 'bot' ],
			],
			'APCOND_BLOCKED' => [
				'conditions' => [ '!', APCOND_BLOCKED ],
			],
			'APCOND_ISBOT' => [
				'conditions' => [ APCOND_ISBOT ],
			],
		];
	}

	/** @dataProvider provideConditionsSupportedForRemoteUsers */
	public function testConditionsSupportedForRemoteUsers( array $conditions, bool $expected ): void {
		$editTrackerMock = $this->createMock( UserEditTracker::class );
		$editTrackerMock->method( 'getUserEditCount' )->willReturn( 20 );
		$editTrackerMock->method( 'getFirstEditTimestamp' )->willReturn(
			MWTimestamp::convert( TS_MW, time() - 60 )
		);
		$this->setService( 'UserEditTracker', $editTrackerMock );

		$registrationLookupMock = $this->createMock( UserRegistrationLookup::class );
		$registrationLookupMock->method( 'getRegistration' )->willReturn(
			MWTimestamp::convert( TS_MW, time() - 120 )
		);
		$this->setService( 'UserRegistrationLookup', $registrationLookupMock );

		$user = new UserIdentityValue( 1, 'Test User', 'otherwiki' );
		$checker = $this->getServiceContainer()->getUserRequirementsConditionChecker();
		$result = $checker->recursivelyCheckCondition( $conditions, $user, false );
		$this->assertSame( $expected, $result );
	}

	public static function provideConditionsSupportedForRemoteUsers(): array {
		return [
			'APCOND_AGE not met' => [
				'conditions' => [ APCOND_AGE, 600 ],
				'expected' => false,
			],
			'APCOND_AGE should be met' => [
				'conditions' => [ APCOND_AGE, 10 ],
				'expected' => true,
			],
			'APCOND_EDITCOUNT not met' => [
				'conditions' => [ APCOND_EDITCOUNT, 100 ],
				'expected' => false,
			],
			'APCOND_EDITCOUNT should be met' => [
				'conditions' => [ APCOND_EDITCOUNT, 10 ],
				'expected' => true,
			],
			'APCOND_AGE_FROM_EDIT not met' => [
				'conditions' => [ APCOND_AGE_FROM_EDIT, 600 ],
				'expected' => false,
			],
			'APCOND_AGE_FROM_EDIT should be met' => [
				'conditions' => [ APCOND_AGE_FROM_EDIT, 10 ],
				'expected' => true,
			],
		];
	}

	/** @dataProvider provideIsRemote */
	public function testAutopromoteHookNotInvokedForNonPerformingUsers( bool $isRemote ): void {
		$this->expectException( InvalidArgumentException::class );
		$conditions = [ 999 ]; // A condition not supported by core

		$hookCalled = false;
		$this->setTemporaryHook(
			'AutopromoteCondition',
			static function ( $type, array $arg, User $hookUser, &$result ) use ( &$hookCalled ) {
				$hookCalled = true;
				$result = true;
			}
		);

		$user = new UserIdentityValue( 1, 'Test User', $isRemote ? 'otherwiki' : UserIdentity::LOCAL );
		$checker = $this->getServiceContainer()->getUserRequirementsConditionChecker();
		$result = $checker->recursivelyCheckCondition( $conditions, $user, false );
		$this->assertFalse( $result );
		$this->assertFalse( $hookCalled, 'Hook should not be called' );
	}

	/** @dataProvider provideIsRemote */
	public function testUserRequirementsConditionHookInvoked( bool $isRemote ): void {
		$conditions = [ 999 ]; // A condition not supported by core

		$hookCalled = false;
		$this->setTemporaryHook(
			'UserRequirementsCondition',
			static function ( $type, array $arg, UserIdentity $hookUser, $isPerformer, &$result ) use ( &$hookCalled ) {
				$hookCalled = true;
				$result = true;
			}
		);

		$user = new UserIdentityValue( 1, 'Test User', $isRemote ? 'otherwiki' : UserIdentity::LOCAL );
		$checker = $this->getServiceContainer()->getUserRequirementsConditionChecker();
		$result = $checker->recursivelyCheckCondition( $conditions, $user, false );
		$this->assertTrue( $result );
		$this->assertTrue( $hookCalled, 'Hook should be called' );
	}

	public static function provideIsRemote(): array {
		return [
			'Local user' => [ false ],
			'Remote user' => [ true ],
		];
	}
}
