<?php

/**
 * @group Database
 */
class DeletedContribsPagerTest extends MediaWikiIntegrationTestCase {
	/** @var DeletedContribsPager */
	private $pager;

	/** @var CommentStore */
	private $commentStore;

	/** @var HookContainer */
	private $hookContainer;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var RevisionStore */
	private $revisionStore;

	protected function setUp(): void {
		parent::setUp();

		$services = $this->getServiceContainer();
		$this->commentStore = $services->getCommentStore();
		$this->hookContainer = $services->getHookContainer();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->loadBalancer = $services->getDBLoadBalancer();
		$this->revisionStore = $services->getRevisionStore();
		$this->pager = $this->getDeletedContribsPager();
	}

	private function getDeletedContribsPager( $target = 'UTSysop', $namespace = 0 ) {
		return new DeletedContribsPager(
			RequestContext::getMain(),
			$this->commentStore,
			$this->hookContainer,
			$this->linkRenderer,
			$this->loadBalancer,
			$this->revisionStore,
			$target,
			$namespace
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
