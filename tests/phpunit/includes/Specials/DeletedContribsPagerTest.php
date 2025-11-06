<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Pager\DeletedContribsPager;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @covers \MediaWiki\Pager\DeletedContribsPager
 */
class DeletedContribsPagerTest extends MediaWikiIntegrationTestCase {
	private static User $user;

	/** @var DeletedContribsPager */
	private $pager;

	/** @var HookContainer */
	private $hookContainer;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var IConnectionProvider */
	private $dbProvider;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var CommentFormatter */
	private $commentFormatter;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var UserFactory */
	private $userFactory;

	protected function setUp(): void {
		parent::setUp();

		$services = $this->getServiceContainer();
		$this->hookContainer = $services->getHookContainer();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->dbProvider = $services->getConnectionProvider();
		$this->revisionStore = $services->getRevisionStore();
		$this->namespaceInfo = $services->getNamespaceInfo();
		$this->commentFormatter = $services->getCommentFormatter();
		$this->linkBatchFactory = $services->getLinkBatchFactory();
		$this->userFactory = $services->getUserFactory();
		$this->pager = $this->getDeletedContribsPager();
	}

	private function getDeletedContribsPager( $target = 'Some test user', $namespace = 0 ) {
		$target = UserIdentityValue::newAnonymous( $target );

		return new DeletedContribsPager(
			$this->hookContainer,
			$this->linkRenderer,
			$this->dbProvider,
			$this->revisionStore,
			$this->namespaceInfo,
			$this->commentFormatter,
			$this->linkBatchFactory,
			$this->userFactory,
			RequestContext::getMain(),
			[ 'namespace' => $namespace ],
			$target
		);
	}

	/**
	 * Flow uses DeletedContribsPager::reallyDoQuery hook to provide something other then
	 * stdClass as a row, and then manually formats its own row in ContributionsLineEnding.
	 * Emulate this behaviour and check that it works.
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

	public static function provideEmptyResultIntegration() {
		$cases = [
			[ 'target' => '', 'namespace' => '' ],
			[ 'target' => '127.0.0.1', 'namespace' => '' ],
			[ 'target' => 'UserWithNoEdits', 'namespace' => 1 ],
		];
		foreach ( $cases as $case ) {
			yield [ $case ];
		}
	}

	/**
	 * Confirm that the query is valid for various filter options.
	 *
	 * @dataProvider provideEmptyResultIntegration
	 */
	public function testEmptyResultIntegration( $options ) {
		$pager = $this->getDeletedContribsPager(
			$options['target'],
			$options['namespace'],
		);
		$this->assertIsString( $pager->getBody() );
		$this->assertSame( 0, $pager->getNumRows() );
	}

	public function testPopulatedIntegrationNoPermissions() {
		$pager = $this->getDeletedContribsPager( self::$user->getName() );

		$this->assertIsString( $pager->getBody() );
		$this->assertSame( 1, $pager->getNumRows() );
	}

	public function testPopulatedIntegrationWithPermissions() {
		$this->setGroupPermissions( [ '*' => [
			'deletedhistory' => true,
			'deletedtext' => true,
			'undelete' => true,
		] ] );

		$pager = $this->getDeletedContribsPager( self::$user->getName() );
		$this->assertIsString( $pager->getBody() );
		$this->assertSame( 2, $pager->getNumRows() );
		$this->assertStringContainsString( '>+9<', $pager->getBody() );
	}

	public function testParentRevisionSizePreloading() {
		$this->setGroupPermissions( [ '*' => [
			'deletedhistory' => true,
			'deletedtext' => true,
			'undelete' => true,
		] ] );

		$pager = $this->getDeletedContribsPager( self::$user->getName() );
		// Make sure the query leaves (at least) one row unselected
		// so that we can test loading from parent revision ids
		TestingAccessWrapper::newFromObject( $pager )->deletedOnly = true;
		$this->assertIsString( $pager->getBody() );
		$this->assertSame( 1, $pager->getNumRows() );
		$this->assertStringContainsString( '>+9<', $pager->getBody() );
	}

	public function addDBDataOnce() {
		self::$user = $this->getTestUser()->getUser();
		$title = Title::makeTitle( NS_MAIN, 'DeletedContribsPagerTest' );

		// Make two edits (one will be revdel'd)
		$this->editPage( $title, 'Test', '', NS_MAIN, self::$user );
		$status = $this->editPage( $title, 'Test content.', '', NS_MAIN, self::$user );

		// Delete the page where the edits were made
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->deletePage( $page );

		// Suppress the second edit
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'archive' )
			->set( [
				'ar_deleted' => RevisionRecord::DELETED_USER | RevisionRecord::DELETED_TEXT,
				// This is to ensure the minor edits path doesn't encounter an error
				'ar_minor_edit' => 1,
			] )
			->where( [
				'ar_rev_id' => $status->getNewRevision()->getId()
			] )
			->execute();
	}
}
