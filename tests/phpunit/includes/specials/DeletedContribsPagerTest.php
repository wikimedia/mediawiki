<?php

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Pager\DeletedContribsPager;
use MediaWiki\Revision\RevisionStore;

/**
 * @group Database
 */
class DeletedContribsPagerTest extends MediaWikiIntegrationTestCase {
	/** @var DeletedContribsPager */
	private $pager;

	/** @var HookContainer */
	private $hookContainer;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var CommentFormatter */
	private $commentFormatter;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	protected function setUp(): void {
		parent::setUp();

		$services = $this->getServiceContainer();
		$this->hookContainer = $services->getHookContainer();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->dbProvider = $services->getDBLoadBalancerFactory();
		$this->revisionStore = $services->getRevisionStore();
		$this->commentFormatter = $services->getCommentFormatter();
		$this->linkBatchFactory = $services->getLinkBatchFactory();
		$this->pager = $this->getDeletedContribsPager();
	}

	private function getDeletedContribsPager( $target = 'Some test user', $namespace = 0 ) {
		return new DeletedContribsPager(
			RequestContext::getMain(),
			$this->hookContainer,
			$this->linkRenderer,
			$this->dbProvider,
			$this->revisionStore,
			$this->commentFormatter,
			$this->linkBatchFactory,
			$target,
			$namespace
		);
	}

	/**
	 * Flow uses DeletedContribsPager::reallyDoQuery hook to provide something other then
	 * stdClass as a row, and then manually formats its own row in ContributionsLineEnding.
	 * Emulate this behaviour and check that it works.
	 *
	 * @covers DeletedContribsPager::formatRow
	 */
	public function testDeletedContribProvidedByHook() {
		$this->setTemporaryHook( 'DeletedContribsPager::reallyDoQuery', static function ( &$data ) {
			$data = [ [ new class() {
				public $ar_timestamp = 12345;
				public $testing = 'TESTING';
				public $ar_namespace = NS_MAIN;
				public $ar_title = 'Test';
				public $ar_rev_id = null;
			} ] ];
		} );
		$this->setTemporaryHook( 'DeletedContributionsLineEnding', function ( $pager, &$ret, $row ) {
			$this->assertSame( 'TESTING', $row->testing );
			$ret .= 'FROM_HOOK!';
		} );
		$pager = $this->getDeletedContribsPager();
		$this->assertStringContainsString( 'FROM_HOOK!', $pager->getBody() );
	}
}
