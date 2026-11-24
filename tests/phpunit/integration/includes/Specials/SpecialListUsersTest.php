<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentity;

/**
 * @covers \MediaWiki\Specials\SpecialListUsers
 * @covers \MediaWiki\Specials\Pager\UsersPager
 * @covers \MediaWiki\Block\DeferredBlockMaterializer
 * @group Database
 */
class SpecialListUsersTest extends SpecialPageTestBase {

	use MockAuthorityTrait;

	public function testExecuteForUsersShown(): void {
		$testUser = $this->getTestUser()->getUserIdentity();

		[ $html ] = $this->executeSpecialPage();

		$this->verifyListUsersForm( $html );
		$this->assertStringContainsString( $testUser->getName(), $html );
		$this->assertStringNotContainsString( '(listusers-noresult)', $html );
	}

	private function verifyListUsersForm( string $html ): void {
		$listUsersForm = $this->assertSelectorMatchesOneElement( $html, '#mw-listusers-form' );
		$this->assertStringContainsString( '(listusers)', $listUsersForm );
		$this->assertStringContainsString( '(listusersfrom)', $listUsersForm );
		$this->assertStringContainsString( '(listusers-editsonly)', $listUsersForm );
		$this->assertStringContainsString( '(listusers-temporarygroupsonly)', $listUsersForm );
		$this->assertStringContainsString( '(listusers-temporaryaccountsonly)', $listUsersForm );
		$this->assertStringContainsString( '(listusers-creationsort)', $listUsersForm );
		$this->assertStringContainsString( '(listusers-desc)', $listUsersForm );
		$this->assertStringContainsString( '(listusers-submit)', $listUsersForm );
	}

	public function testDeferredBlockNotShown(): void {
		$testUser = $this->getTestUser()->getUser();
		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlockWithParams( [
			'targetUser' => $testUser,
			'by' => $this->getTestSysop()->getUser(),
			'expiry' => 'infinity',
			'deferred' => true,
		] );

		[ $html ] = $this->executeSpecialPage();
		$this->assertStringContainsString( $testUser->getName(), $html );
		$this->assertStringNotContainsString( '(listusers-blocked', $html );

		// Once the block materializes, it is shown
		$materializer = $this->getServiceContainer()->getDeferredBlockMaterializer();
		$materializer->materialize( $materializer->getDeferredBlocks( $testUser, null )[0] );
		[ $html ] = $this->executeSpecialPage();
		$this->assertStringContainsString( '(listusers-blocked', $html );
	}

	public function testExecuteForNoUsersShown(): void {
		$testUser = $this->getTestUser()->getUserIdentity();

		[ $html ] = $this->executeSpecialPage( 'sysop' );

		$this->verifyListUsersForm( $html );
		$this->assertStringNotContainsString( $testUser->getName(), $html );
		$this->assertStringContainsString( '(listusers-noresult)', $html );
	}

	/**
	 * Creates a user and hides their username using a block with the 'hideuser' option.
	 */
	private function getHiddenUser(): UserIdentity {
		$hiddenUser = $this->getMutableTestUser()->getUserIdentity();
		$blockStatus = $this->getServiceContainer()->getBlockUserFactory()
			->newBlockUser(
				$hiddenUser,
				new UltimateAuthority( $this->getTestSysop()->getUserIdentity() ),
				'infinity',
				'',
				[ 'isHideUser' => true ]
			)
			->placeBlock();
		$this->assertStatusGood( $blockStatus );
		return $hiddenUser;
	}

	/** @dataProvider provideHiddenUsersShownOnlyWhenUserCanSeeThem */
	public function testHiddenUsersShownOnlyWhenUserCanSeeThem(
		bool $showHiddenUsers,
		bool $userCanSeeHiddenUsers,
		bool $shouldShowHiddenUsers
	): void {
		$hiddenUser = $this->getHiddenUser();
		$visibleUser = $this->getTestUser()->getUserIdentity();

		[ $html ] = $this->executeSpecialPage(
			'',
			new FauxRequest( $showHiddenUsers ? [ 'showHiddenUsers' => 1 ] : [] ),
			null,
			$userCanSeeHiddenUsers ?
				$this->mockRegisteredUltimateAuthority() :
				$this->mockRegisteredAuthorityWithoutPermissions( [ 'hideuser' ] )
		);

		$this->verifyListUsersForm( $html );
		$this->assertStringContainsString( $visibleUser->getName(), $html );

		if ( $userCanSeeHiddenUsers ) {
			$this->assertStringContainsString( '(listusers-showhiddenusers)', $html );
		} else {
			$this->assertStringNotContainsString( '(listusers-showhiddenusers)', $html );
		}
		if ( $shouldShowHiddenUsers ) {
			$this->assertStringContainsString( $hiddenUser->getName(), $html );
		} else {
			$this->assertStringNotContainsString( $hiddenUser->getName(), $html );
		}
	}

	public static function provideHiddenUsersShownOnlyWhenUserCanSeeThem(): array {
		return [
			'Hidden users filter disabled and user cannot see hidden users' => [
				'showHiddenUsers' => false,
				'userCanSeeHiddenUsers' => false,
				'shouldShowHiddenUsers' => false,
			],
			'Hidden users filter enabled and user cannot see hidden users' => [
				'showHiddenUsers' => true,
				'userCanSeeHiddenUsers' => false,
				'shouldShowHiddenUsers' => false,
			],
			'Hidden users filter disabled and user can see hidden users' => [
				'showHiddenUsers' => false,
				'userCanSeeHiddenUsers' => true,
				'shouldShowHiddenUsers' => false,
			],
			'Hidden users filter enabled and user can see hidden users' => [
				'showHiddenUsers' => true,
				'userCanSeeHiddenUsers' => true,
				'shouldShowHiddenUsers' => true,
			],
		];
	}

	public function testGetSubpagesForPrefixSearch(): void {
		$this->assertSame(
			$this->getServiceContainer()->getUserGroupManager()->listAllGroups(),
			$this->newSpecialPage()->getSubpagesForPrefixSearch()
		);
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Listusers' );
	}
}
