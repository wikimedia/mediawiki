<?php

use MediaWiki\Specials\SpecialDeletedContributions;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialDeletedContributions
 */
class SpecialDeletedContributionsTest extends SpecialPageTestBase {
	private static User $sysop;

	protected function newSpecialPage(): SpecialDeletedContributions {
		$services = $this->getServiceContainer();

		return new SpecialDeletedContributions(
			$services->getPermissionManager(),
			$services->getConnectionProvider(),
			$services->getRevisionStore(),
			$services->getNamespaceInfo(),
			$services->getUserNameUtils(),
			$services->getUserNamePrefixSearch(),
			$services->getUserOptionsLookup(),
			$services->getCommentFormatter(),
			$services->getLinkBatchFactory(),
			$services->getUserFactory(),
			$services->getUserIdentityLookup(),
			$services->getDatabaseBlockStore()
		);
	}

	public function testExecuteNoTarget() {
		[ $html ] = $this->executeSpecialPage(
			'',
			null,
			null,
			self::$sysop,
		);
		$this->assertStringNotContainsString( 'mw-pager-body', $html );
	}

	public function testExecuteNoResults() {
		[ $html ] = $this->executeSpecialPage(
			'127.0.0.1',
			null,
			null,
			self::$sysop,
		);
		$this->assertStringNotContainsString( 'mw-pager-body', $html );
	}

	public function testExecuteNamespaceFilter() {
		[ $html ] = $this->executeSpecialPage(
			self::$sysop->getName(),
			new FauxRequest( [
				'namespace' => NS_TALK,
			] ),
			null,
			self::$sysop,
		);
		$this->assertStringContainsString( 'mw-pager-body', $html );
	}

	public function addDBDataOnce() {
		self::$sysop = $this->getTestSysop()->getUser();

		$title = Title::makeTitle( NS_MAIN, 'DeletedContribsPagerTest' );
		$title = Title::makeTitle( NS_TALK, 'DeletedContribsPagerTest' );

		// Make two edits (one will be suppressed)
		$this->editPage( $title, '', '', NS_MAIN, self::$sysop );
		$status = $this->editPage( $title, 'Test content.', '', NS_MAIN, self::$sysop );

		// Delete the page where the edits were made
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->deletePage( $page );
	}
}
