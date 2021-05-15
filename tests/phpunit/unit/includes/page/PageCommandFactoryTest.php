<?php

namespace MediaWiki\Tests\Unit\Page;

use ContentModelChange;
use HashConfig;
use MediaWiki\Page\PageCommandFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\RollbackPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\MockServiceDependenciesTrait;
use MediaWiki\User\UserIdentity;
use MediaWikiUnitTestCase;
use MergeHistory;
use MockTitleTrait;
use MovePage;
use Title;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use WikiPage;

/**
 * @covers \MediaWiki\Page\PageCommandFactory
 * @author DannyS712
 */
class PageCommandFactoryTest extends MediaWikiUnitTestCase {
	use MockServiceDependenciesTrait;
	use MockTitleTrait;

	private function getFactory() {
		// Create a PageCommandFactory with all of the services needed
		$config = new HashConfig( [
			// RollbackPage
			'UseRCPatrol' => true,
			'DisableAnonTalk' => false,

			// MovePage
			'CategoryCollation' => 'uppercase',
			'MaximumMovedPages' => 100,
		] );

		// MergeHistory constructor includes some method calls that we need to be able
		// to support - retrieve the primary database from the load balancer, and call
		// some methods. We don't make any assertions about what is called, because
		// these mocks are used for the other tests and we don't really care, we are
		// just making sure that PageCommandFactory works
		$database = $this->createMock( IDatabase::class );
		// Fetching various timestamps
		$database->method( 'selectField' )->willReturn( '20210101120000' );
		// Looks like a valid timestamp, we don't care
		$database->method( 'timestamp' )->willReturn( '20210101120000' );
		// Doesn't matter
		$database->method( 'addQuotes' )->willReturn( 'quotes' );

		$loadBalancer = $this->createMock( ILoadBalancer::class );
		$loadBalancer->method( 'getConnection' )->willReturn( $database );

		// Helper method from MockServiceDependenciesTrait
		return $this->newServiceInstance(
			PageCommandFactory::class,
			[
				'config' => $config,
				'loadBalancer' => $loadBalancer,
			]
		);
	}

	public function testContentModelChange() {
		$contentModelChange = $this->getFactory()->newContentModelChange(
			$this->createMock( Authority::class ),
			$this->createMock( WikiPage::class ),
			CONTENT_MODEL_TEXT
		);
		$this->assertInstanceOf(
			ContentModelChange::class,
			$contentModelChange,
			'Creating ContentModelChange works'
		);
	}

	public function testMergeHistory() {
		// Using Title objects from MockTitleTrait to support the ->getArticleId() calls
		$mergeHistory = $this->getFactory()->newMergeHistory(
			$this->makeMockTitle( 'Source' ),
			$this->makeMockTitle( 'Destination' ),
			null // $timestamp
		);
		$this->assertInstanceOf(
			MergeHistory::class,
			$mergeHistory,
			'Creating MergeHistory works'
		);
	}

	public function testMovePage() {
		$movePage = $this->getFactory()->newMovePage(
			$this->createNoOpMock( Title::class ),
			$this->createNoOpMock( Title::class )
		);
		$this->assertInstanceOf(
			MovePage::class,
			$movePage,
			'Creating MovePage works'
		);
	}

	public function testRollbackPage() {
		$rollbackPage = $this->getFactory()->newRollbackPage(
			$this->createMock( PageIdentity::class ),
			$this->createMock( Authority::class ),
			$this->createMock( UserIdentity::class )
		);
		$this->assertInstanceOf(
			RollbackPage::class,
			$rollbackPage,
			'Creating RollbackPage works'
		);
	}

}
