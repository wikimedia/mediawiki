<?php

namespace MediaWiki\Tests\Maintenance;

use BlockUsers;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;

/**
 * @covers \BlockUsers
 * @group Database
 * @author Dreamy Jazz
 */
class BlockUsersTest extends MaintenanceBaseTestCase {
	use MockAuthorityTrait;

	protected function getMaintenanceClass() {
		return BlockUsers::class;
	}

	private function commonTestExecute( $options, $fileContents ) {
		// Add the specified $options
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		// Create a temporary file, write $fileContents to it, and then pass the filename in argv.
		$file = $this->getNewTempFile();
		file_put_contents( $file, $fileContents );
		$this->maintenance->setArg( 'file', $file );
		// Call ::execute
		$this->maintenance->execute();
	}

	/**
	 * @param int $count
	 * @return UserIdentity[]
	 */
	private function getTestUnblockedUsers( int $count ): array {
		$returnArray = [];
		for ( $i = 0; $i < $count; $i++ ) {
			$returnArray[] = $this->getMutableTestUser()->getUserIdentity();
		}
		return $returnArray;
	}

	/**
	 * @param int $count
	 * @return UserIdentity[]
	 */
	private function getTestBlockedUsers( int $count ): array {
		$returnArray = $this->getTestUnblockedUsers( $count );
		$blockManager = $this->getServiceContainer()->getBlockManager();
		$blockUserFactory = $this->getServiceContainer()->getBlockUserFactory();
		foreach ( $returnArray as $user ) {
			$blockUserFactory->newBlockUser( $user, $this->mockRegisteredUltimateAuthority(), 'infinite', 'test' )
				->placeBlock();
			$this->assertNotNull( $blockManager->getBlock( $user, null ) );
		}
		return $returnArray;
	}

	/** @dataProvider provideExecuteForUnblock */
	public function testExecuteForUnblock( $blockedUserCount, $notBlockedUserCount ) {
		$blockedUsers = $this->getTestBlockedUsers( $blockedUserCount );
		$unblockedUsers = $this->getTestUnblockedUsers( $notBlockedUserCount );
		// Generate the file contents by combining the $blockedUsers and $unblockedUsers arrays
		$fileContents = '';
		$expectedOutputRegex = '/';
		foreach ( $blockedUsers as $user ) {
			$fileContents .= $user->getName() . PHP_EOL;
			$userNameForRegex = preg_quote( $user->getName() );
			$expectedOutputRegex .= ".*Unblocking '$userNameForRegex' succeeded.\n";
		}
		foreach ( $unblockedUsers as $user ) {
			$fileContents .= $user->getName() . PHP_EOL;
			$userNameForRegex = preg_quote( $user->getName() );
			$expectedOutputRegex .= ".*Unblocking '$userNameForRegex' failed.*\n";
		}
		$options['unblock'] = true;
		$this->commonTestExecute( $options, $fileContents );
		$this->expectOutputRegex( $expectedOutputRegex . '/' );
		// Verify that the blocked users are no longer blocked.
		$blockManager = $this->getServiceContainer()->getBlockManager();
		foreach ( $blockedUsers as $user ) {
			$blockManager->clearUserCache( $user );
			$this->assertNull( $blockManager->getBlock( $user, null ), 'User was not actually unblocked.' );
		}
	}

	public static function provideExecuteForUnblock() {
		return [
			'2 blocked users and 1 unblocked user' => [ 2, 1 ],
			'3 blocked users' => [ 3, 0 ],
		];
	}

	/** @dataProvider provideExecuteForBlock */
	public function testExecuteForBlock( $options, $blockedUserCount, $notBlockedUserCount ) {
		$blockedUsers = $this->getTestBlockedUsers( $blockedUserCount );
		$unblockedUsers = $this->getTestUnblockedUsers( $notBlockedUserCount );
		// Generate the file contents by combining the $blockedUsers and $unblockedUsers arrays
		$fileContents = '';
		$expectedOutputRegex = '/';
		foreach ( $unblockedUsers as $user ) {
			$fileContents .= $user->getName() . PHP_EOL;
			$userNameForRegex = preg_quote( $user->getName() );
			$expectedOutputRegex .= ".*Blocking '$userNameForRegex' succeeded.\n";
		}
		foreach ( $blockedUsers as $user ) {
			$fileContents .= $user->getName() . PHP_EOL;
			$userNameForRegex = preg_quote( $user->getName() );
			$expectedOutputRegex .= ".*Blocking '$userNameForRegex' failed.*\n";
		}
		$this->commonTestExecute( $options, $fileContents );
		$this->expectOutputRegex( $expectedOutputRegex . '/' );
		// Verify that the blocked users are no longer blocked.
		$blockManager = $this->getServiceContainer()->getBlockManager();
		foreach ( $unblockedUsers as $user ) {
			$blockManager->clearUserCache( $user );
			$actualBlock = $blockManager->getBlock( $user, null );
			$this->assertNotNull( $actualBlock, 'User was not blocked' );
			// Verify that the block parameters are as expected.
			$this->assertSame( $options['reason'] ?? '', $actualBlock->getReasonComment()->text );
			if ( !isset( $options['expiry'] ) || wfIsInfinity( $options['expiry'] ) ) {
				$this->assertTrue( wfIsInfinity( $actualBlock->getExpiry() ) );
			} else {
				$this->assertSame( $options['expiry'], $actualBlock->getExpiry() );
			}
			$this->assertSame(
				$options['performer'] ?? User::MAINTENANCE_SCRIPT_USER,
				$actualBlock->getBlocker()->getName()
			);
			$blockParameters = [
				'isCreateAccountBlocked' => !isset( $options['allow-createaccount'] ),
				'isEmailBlocked' => !isset( $options['allow-email'] ),
				'isUsertalkEditAllowed' => isset( $options['allow-talkedit'] ),
				'isHardBlock' => !isset( $options['disable-hardblock'] ),
				'isAutoblocking' => !isset( $options['disable-autoblock'] ),
			];
			foreach ( $blockParameters as $methodName => $expectedReturnValue ) {
				$this->assertSame(
					$expectedReturnValue,
					$actualBlock->$methodName(),
					"::$methodName returned an unexpected value"
				);
			}
		}
	}

	public static function provideExecuteForBlock() {
		return [
			'2 blocked users and 1 unblocked user, with specified reason' => [ [ 'reason' => 'testing' ], 2, 1 ],
			'3 unblocked users with most options set' => [
				[
					'allow-createaccount' => 1, 'allow-email' => 1, 'allow-talkedit' => 1, 'disable-hardblock' => 1,
					'disable-autoblock' => 1,
				],
				3, 0,
			],
		];
	}

	public function testExecuteForBlockWithSpecifiedPerformer() {
		$this->testExecuteForBlock( [ 'performer' => $this->getTestSysop()->getUserIdentity()->getName() ], 1, 0 );
	}

	public function testExecuteWhenPerformerNameInvalid() {
		// Set wgMaxNameChars to 3, so that the performer username will be invalid
		$this->overrideConfigValue( MainConfigNames::MaxNameChars, 3 );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Unable to parse.*username/' );
		$this->commonTestExecute( [ 'performer' => 'Username-which-is-too-long' ], '' );
	}
}
