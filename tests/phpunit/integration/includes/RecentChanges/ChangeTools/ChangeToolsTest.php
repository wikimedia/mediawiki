<?php

namespace MediaWiki\Tests\Integration\RecentChanges\ChangeTools;

use MediaWiki\Context\RequestContext;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\RecentChanges\ChangeTools\ChangeTools
 * @covers \MediaWiki\RecentChanges\ChangeTools\ChangeToolsFactory
 *
 * @group Database
 */
class ChangeToolsTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->clearHook( 'HistoryTools' );
	}

	public function testToHtml(): void {
		$page = $this->getExistingTestPage( 'ChangeToolsTestToHtml' );
		$previousRev = $page->getRevisionRecord();
		// Edit the page so a rollback link is generated
		$this->editPage( $page, 'Test' );

		$context = RequestContext::getMain();
		$context->setAuthority( $this->mockRegisteredUltimateAuthority() );
		$changeTools = $this->getServiceContainer()->getChangeToolsFactory()->buildChangeTools(
			$page->getRevisionRecord(),
			$previousRev,
			true,
			$context,
		);

		// We can't use assertEquals here since the rollback link contains a token
		$this->assertStringStartsWith(
			' <span class="mw-changeslist-links mw-pager-tools mw-change-tools"><span><span class="mw-rollback-link">',
			$changeTools->toHtml(),
			'ChangeTools HTML should contain a rollback link wrapped in the expected HTML structure.'
		);
		$this->assertStringContainsString(
			'<span class="mw-history-undo mw-change-tools-undo">',
			$changeTools->toHtml(),
			'ChangeTools HTML should contain an undo link when a previous revision is provided',
		);
	}

	public function testToHtml_Empty(): void {
		$page = $this->getExistingTestPage( 'ChangeToolsTestToHtml_Empty' );
		$previousRev = $page->getRevisionRecord();
		$this->editPage( $page, 'Test' );

		$context = RequestContext::getMain();
		$context->setAuthority( $this->mockRegisteredNullAuthority() );
		$changeTools = $this->getServiceContainer()->getChangeToolsFactory()->buildChangeTools(
			$page->getRevisionRecord(),
			$previousRev,
			true,
			$context,
		);

		$this->assertSame(
			'',
			$changeTools->toHtml(),
			"ChangeTools HTML should be empty when the user doesn't have permissions to use any tools",
		);
	}

	public function testToHtml_Hook(): void {
		$html = '<a href="/">ChangeToolsTest Link</a>';
		$this->setTemporaryHook(
			'HistoryTools',
			static function ( $revRecord, &$links, $prevRevRecord, $userIdentity ) use ( $html ) {
				$links['change-tools-test'] = $html;
			}
		);

		$page = $this->getExistingTestPage();
		$changeTools = $this->getServiceContainer()->getChangeToolsFactory()->buildChangeTools(
			$page->getRevisionRecord(),
			null,
			true,
			RequestContext::getMain(),
		);

		$this->assertStringContainsString(
			$html,
			$changeTools->toHtml(),
			'ChangeTools HTML should contain custom tools added through the HistoryTools hook'
		);
	}

	public function testShouldPreventClickjacking_True(): void {
		$page = $this->getExistingTestPage();
		// Edit the page so a rollback link is generated
		$this->editPage( $page, 'Test' );

		$context = RequestContext::getMain();
		$context->setAuthority( $this->mockRegisteredUltimateAuthority() );
		$changeTools = $this->getServiceContainer()->getChangeToolsFactory()->buildChangeTools(
			$page->getRevisionRecord(),
			null,
			true,
			$context,
		);

		$this->assertTrue(
			$changeTools->shouldPreventClickjacking(),
			'shouldPreventClickjacking should return true when a rollback link is generated'
		);
	}

	public function testShouldPreventClickjacking_False(): void {
		$page = $this->getExistingTestPage();

		$context = RequestContext::getMain();
		$context->setAuthority( $this->mockRegisteredUltimateAuthority() );
		$changeTools = $this->getServiceContainer()->getChangeToolsFactory()->buildChangeTools(
			$page->getRevisionRecord(),
			null,
			false,
			$context,
		);

		$this->assertFalse(
			$changeTools->shouldPreventClickjacking(),
			'shouldPreventClickjacking should return false when no rollback link is generated'
		);
	}

}
