<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Page\MovePage;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiUnitTestCase;
use MockTitleTrait;

/**
 * @coversDefaultClass MediaWiki\Page\MovePage
 * @package MediaWiki\Tests\Unit
 * @method MovePage newServiceInstance(string $serviceClass, array $parameterOverrides)
 */
class MovePageTest extends MediaWikiUnitTestCase {
	use MockTitleTrait;
	use MockAuthorityTrait;
	use MockServiceDependenciesTrait;

	public function provideCheckPermissions() {
		yield 'all good and allowed' => [
			'authority' => $this->mockRegisteredUltimateAuthority(),
			'good' => true,
		];
		yield 'can not move' => [
			'authority' => $this->mockAnonAuthority( function (
				string $permission,
				PageIdentity $page,
				PermissionStatus $status
			) {
				if ( $permission === 'move' ) {
					$this->assertSame( 'Existent', $page->getDBkey() );
					$status->fatal( 'test' );
					return false;
				}
				return true;
			} ),
			'good' => false,
		];
		yield 'can not edit old page' => [
			'authority' => $this->mockAnonAuthority( static function (
				string $permission,
				PageIdentity $page,
				PermissionStatus $status
			) {
				if ( $permission === 'edit' && $page->getDBkey() === 'Existent' ) {
					$status->fatal( 'test' );
					return false;
				}
				return true;
			} ),
			'good' => false,
		];
		yield 'can not move-target' => [
			'authority' => $this->mockAnonAuthority( function (
				string $permission,
				PageIdentity $page,
				PermissionStatus $status
			) {
				if ( $permission === 'move-target' ) {
					$this->assertSame( 'Existent2', $page->getDBkey() );
					$status->fatal( 'test' );
					return false;
				}
				return true;
			} ),
			'good' => false,
		];
		yield 'can not edit new page' => [
			'authority' => $this->mockAnonAuthority( static function (
				string $permission,
				PageIdentity $page,
				PermissionStatus $status
			) {
				if ( $permission === 'edit' && $page->getDBkey() === 'Existent2' ) {
					$status->fatal( 'test' );
					return false;
				}
				return true;
			} ),
			'good' => false,
		];
	}

	/**
	 * @dataProvider provideCheckPermissions
	 * @covers MediaWiki\Page\MovePage::checkPermissions
	 * @covers MediaWiki\Page\MovePage::authorizeMove
	 * @covers MediaWiki\Page\MovePage::probablyCanMove
	 */
	public function testCheckPermissions( Authority $authority, bool $good ) {
		$spamChecker = $this->createNoOpMock( SpamChecker::class, [ 'checkSummary' ] );
		$spamChecker->method( 'checkSummary' )->willReturn( false );
		$mp = $this->newServiceInstance(
			MovePage::class,
			[
				'oldTitle' => $this->makeMockTitle( 'Existent' ),
				'newTitle' => $this->makeMockTitle( 'Existent2' ),
				'spamChecker' => $spamChecker,
			]
		);
		foreach ( [ 'checkPermissions', 'authorizeMove', 'probablyCanMove' ] as $method ) {
			$permissionStatus = $mp->$method( $authority, 'Testing' );
			$this->assertSame( $good, $permissionStatus->isGood() );
		}
	}

	/**
	 * @covers MediaWiki\Page\MovePage::checkPermissions
	 * @covers MediaWiki\Page\MovePage::authorizeMove
	 * @covers MediaWiki\Page\MovePage::probablyCanMove
	 */
	public function testCheckPermissions_spam() {
		$spamChecker = $this->createNoOpMock( SpamChecker::class, [ 'checkSummary' ] );
		$spamChecker->method( 'checkSummary' )
			->willReturnCallback( static function ( string $reason ) {
				return $reason === 'SPAM';
			} );
		$mp = $this->newServiceInstance(
			MovePage::class,
			[
				'oldTitle' => $this->makeMockTitle( 'Existent' ),
				'newTitle' => $this->makeMockTitle( 'Existent2' ),
				'spamChecker' => $spamChecker,
			]
		);
		foreach ( [ 'checkPermissions', 'authorizeMove', 'probablyCanMove' ] as $method ) {
			$notSpamStatus = $mp->$method( $this->mockRegisteredUltimateAuthority(), 'NOT_SPAM' );
			$this->assertStatusGood( $notSpamStatus );
			$spamStatus = $mp->$method( $this->mockRegisteredUltimateAuthority(), 'SPAM' );
			$this->assertStatusError( 'spamprotectiontext', $spamStatus );
		}
	}
}
