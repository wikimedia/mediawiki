<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentity;

/**
 * @group Database
 */
class PatrolManagerTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	private const USER_COMMENT = '<User comment about action>';

	protected PageIdentity $title;
	protected UserIdentity $user;

	protected function setUp(): void {
		parent::setUp();

		$this->title = PageIdentityValue::localIdentity( 17, NS_MAIN, 'SomeTitle' );
		$this->user = $this->getTestUser()->getUser();

		$this->overrideConfigValues( [
			MainConfigNames::UseRCPatrol => true,
			MainConfigNames::UseNPPatrol => false,
		] );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\PatrolManager::markPatrolled
	 */
	public function testMarkPatrolledPermissions() {
		$rc = $this->getDummyEditRecentChange();
		$performer = $this->mockRegisteredAuthority( static function (
			string $permission,
			PageIdentity $page,
			PermissionStatus $status
		) {
			if ( $permission === 'patrol' ) {
				$status->fatal( 'missing-patrol' );
				return false;
			}
			return true;
		} );
		$status = $this->getServiceContainer()->getPatrolManager()->markPatrolled(
			$rc,
			$performer
		);
		$this->assertStatusError( 'missing-patrol', $status );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\PatrolManager::markPatrolled
	 */
	public function testMarkPatrolledPermissions_Hook() {
		$rc = $this->getDummyEditRecentChange();
		$this->setTemporaryHook( 'MarkPatrolled', static function () {
			return false;
		} );
		$status = $this->getServiceContainer()->getPatrolManager()->markPatrolled(
			$rc,
			$this->mockRegisteredUltimateAuthority()
		);
		$this->assertStatusError( 'hookaborted', $status );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\PatrolManager::markPatrolled
	 */
	public function testMarkPatrolledPermissions_Self() {
		$rc = $this->getDummyEditRecentChange();
		$status = $this->getServiceContainer()->getPatrolManager()->markPatrolled(
			$rc,
			$this->mockUserAuthorityWithoutPermissions( $this->user, [ 'autopatrol' ] )
		);
		$this->assertStatusError( 'markedaspatrollederror-noautopatrol', $status );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\PatrolManager::markPatrolled
	 */
	public function testMarkPatrolledPermissions_NoRcPatrol() {
		$this->overrideConfigValue( MainConfigNames::UseRCPatrol, false );
		$rc = $this->getDummyEditRecentChange();
		$status = $this->getServiceContainer()->getPatrolManager()->markPatrolled(
			$rc,
			$this->mockRegisteredUltimateAuthority()
		);
		$this->assertStatusError( 'rcpatroldisabled', $status );
	}

	/**
	 * @covers \MediaWiki\RecentChanges\PatrolManager::markPatrolled
	 */
	public function testMarkPatrolled() {
		$rc = $this->getDummyEditRecentChange();
		$status = $this->getServiceContainer()->getPatrolManager()->markPatrolled(
			$rc,
			$this->mockUserAuthorityWithPermissions( $this->user, [ 'patrol', 'autopatrol' ] )
		);
		$this->assertStatusGood( $status );

		$reloadedRC = $this->getServiceContainer()
			->getRecentChangeLookup()
			->getRecentChangeById( $rc->getAttribute( 'rc_id' ) );
		$this->assertSame( '1', $reloadedRC->getAttribute( 'rc_patrolled' ) );
	}

	private function getDummyEditRecentChange(): RecentChange {
		$recentChangeStore = $this->getServiceContainer()->getRecentChangeStore();
		$rc = $recentChangeStore->createEditRecentChange(
			MWTimestamp::now(),
			$this->title,
			false,
			$this->user,
			self::USER_COMMENT,
			0,
			false
		);
		$recentChangeStore->insertRecentChange( $rc );
		return $rc;
	}
}
