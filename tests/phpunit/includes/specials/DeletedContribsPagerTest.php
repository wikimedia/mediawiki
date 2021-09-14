<?php

use MediaWiki\MediaWikiServices;

/**
 * @group Database
 */
class DeletedContribsPagerTest extends MediaWikiIntegrationTestCase {
	/** @var DeletedContribsPager */
	private $pager;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var HookContainer */
	private $hookContainer;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var CommentStore */
	private $commentStore;

	protected function setUp(): void {
		parent::setUp();

		$services = MediaWikiServices::getInstance();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->revisionStore = $services->getRevisionStore();
		$this->hookContainer = $services->getHookContainer();
		$this->loadBalancer = $services->getDBLoadBalancer();
		$this->commentStore = $services->getCommentStore();
		$this->pager = $this->getDeletedContribsPager();
	}

	private function getDeletedContribsPager( $target = 'UTSysop', $namespace = 0 ) {
		return new DeletedContribsPager(
			new RequestContext(),
			$target,
			$namespace,
			$this->linkRenderer,
			$this->hookContainer,
			$this->loadBalancer,
			$this->commentStore,
			$this->revisionStore
		);
	}

	/**
	 * Flow uses DeletedContribsPager::reallyDoQuery hook to provide something other then
	 * stdClass as a row, and then manually formats it's own row in ContributionsLineEnding.
	 * Emulate this behaviour and check that it works.
	 *
	 * @covers DeletedContribsPager::formatRow
	 */
	public function testDeletedContribProvidedByHook() {
		$this->setTemporaryHook( 'DeletedContribsPager::reallyDoQuery', static function ( &$data ) {
			$data = [ [ new class() {
				public $ar_timestamp = 12345;
				public $testing = 'TESTING';
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
