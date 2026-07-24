<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Tests\Specials\SpecialPageTestBase;

/**
 * @covers \MediaWiki\Specials\SpecialListUsers
 * @covers \MediaWiki\Specials\Pager\UsersPager
 * @group Database
 */
class SpecialListUsersTest extends SpecialPageTestBase {
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

	public function testExecuteForNoUsersShown(): void {
		$testUser = $this->getTestUser()->getUserIdentity();

		[ $html ] = $this->executeSpecialPage( 'sysop' );

		$this->verifyListUsersForm( $html );
		$this->assertStringNotContainsString( $testUser->getName(), $html );
		$this->assertStringContainsString( '(listusers-noresult)', $html );
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
