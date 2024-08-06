<?php

use MediaWiki\Specials\SpecialDeletedContributions;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialDeletedContributions
 */
class SpecialDeletedContributionsTest extends SpecialPageTestBase {
	protected function newSpecialPage(): SpecialDeletedContributions {
		$services = $this->getServiceContainer();

		return new SpecialDeletedContributions(
			$services->getPermissionManager(),
			$services->getConnectionProvider(),
			$services->getRevisionStore(),
			$services->getNamespaceInfo(),
			$services->getUserFactory(),
			$services->getUserNameUtils(),
			$services->getUserNamePrefixSearch(),
			$services->getCommentFormatter(),
			$services->getLinkBatchFactory(),
			$services->getDatabaseBlockStore()
		);
	}

	public function testExecuteNoResults() {
		$sysop = $this->getTestSysop()->getUser();
		[ $html ] = $this->executeSpecialPage(
			$sysop->getName(),
			null,
			null,
			$sysop,
		);
		$this->assertStringNotContainsString( 'mw-pager-body', $html );
	}
}
