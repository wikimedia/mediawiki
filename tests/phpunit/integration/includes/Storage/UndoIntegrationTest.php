<?php

namespace MediaWiki\Tests\Storage;

use Article;
use EditPage;
use FauxRequest;
use McrUndoAction;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\SlotRecord;
use MediaWikiIntegrationTestCase;
use RequestContext;
use Title;
use User;
use WikiPage;
use WikitextContent;

/**
 * Integration tests for undos.
 * TODO: This should also test edits with multiple slots.
 *
 * @covers McrUndoAction
 * @covers WikiPage
 * @covers EditPage
 *
 * @group Database
 * @group medium
 */
class UndoIntegrationTest extends MediaWikiIntegrationTestCase {

	private const PAGE_NAME = 'McrUndoTestPage';

	protected function setUp() : void {
		parent::setUp();

		// Clean up these tables after each test
		$this->tablesUsed = [
			'page',
			'revision',
			'comment',
			'text',
			'content'
		];
	}

	/**
	 * Creates a new McrUndoAction object for testing.
	 *
	 * @param RequestContext $context
	 * @param Article $article
	 * @param array $params POST/GET parameters passed to the action on submit
	 *
	 * @return McrUndoAction
	 */
	private function makeNewMcrUndoAction(
		RequestContext $context,
		Article $article,
		array $params
	) : McrUndoAction {
		$request = new FauxRequest( $params );
		$request->setVal( 'wpSave', '' );
		$context->setRequest( $request );

		$outputPage = $this->createMock( 'OutputPage' );
		$context->setOutput( $outputPage );
		$context->setUser( $this->getTestSysop()->getUser() );

		return new class( $article, $context ) extends McrUndoAction {
			public function show() {
				// Instead of trying to actually display anything, just initialize the class.
				$this->checkCanExecute( $this->getUser() );
			}
		};
	}

	/**
	 * Convenience function for setting up a test page and filling it with edits.
	 * @param string[] $revisions
	 *
	 * @return array
	 */
	private function setUpPageForTesting( array $revisions ) : array {
		$this->getExistingTestPage( self::PAGE_NAME );
		$revisionIds = [];
		foreach ( $revisions as $revisionContent ) {
			$revisionIds[] = $this->editPage( self::PAGE_NAME, $revisionContent )
				->value['revision-record']->getId();
		}
		$revisionIds['false'] = false;
		return $revisionIds;
	}

	/**
	 * @param string $newContent
	 * @param array $revisionIds
	 * @param bool $isExactRevert
	 * @param int|string $oldestRevertedRevIndex
	 * @param int|string $newestRevertedRevIndex
	 * @param int|string $originalRevIndex
	 */
	private function setPageSaveCompleteHook(
		string $newContent,
		array $revisionIds,
		bool $isExactRevert,
		$oldestRevertedRevIndex,
		$newestRevertedRevIndex,
		$originalRevIndex
	) {
		// set up a temporary hook with asserts
		$this->setTemporaryHook(
			'PageSaveComplete',
			function (
				WikiPage $wikiPage,
				User $user,
				string $summary,
				int $flags,
				RevisionStoreRecord $revisionRecord,
				EditResult $editResult
			) use (
				$newContent,
				$revisionIds,
				$isExactRevert,
				$oldestRevertedRevIndex,
				$newestRevertedRevIndex,
				$originalRevIndex
			) {
				$this->assertTrue(
					$editResult->isRevert(),
					'EditResult::isRevert()'
				);
				$this->assertSame(
					EditResult::REVERT_UNDO,
					$editResult->getRevertMethod(),
					'EditResult::getRevertMethod()'
				);
				$this->assertArrayEquals( [ 'mw-undo' ],
					$editResult->getRevertTags(),
					false,
					false,
					'EditResult::getRevertTags()'
				);
				$this->assertSame(
					$isExactRevert,
					$editResult->isExactRevert(),
					'EditResult::isExactRevert()'
				);
				$this->assertSame(
					$revisionIds[$oldestRevertedRevIndex],
					$editResult->getOldestRevertedRevisionId(),
					'EditResult::getOldestRevertedRevisionId()'
				);
				$this->assertSame(
					$revisionIds[$newestRevertedRevIndex],
					$editResult->getNewestRevertedRevisionId(),
					'EditResult::getNewestRevertedRevisionId()'
				);
				$this->assertSame(
					$revisionIds[$originalRevIndex],
					$editResult->getOriginalRevisionId(),
					'EditResult::getOriginalRevisionId()'
				);
				$mainContent = $revisionRecord->getContent( SlotRecord::MAIN );
				/** @var WikitextContent $mainContent */
				$this->assertSame(
					$newContent,
					$mainContent->getText(),
					'RevisionRecord::getContent()'
				);
			}
		);
	}

	/**
	 * Provides test cases for well-formed undos.
	 *
	 * @return array[]
	 */
	public function provideUndos() {
		return [
			'undoing a single revision' => [
				[ '1', '2' ],
				'1',
				0,
				1,
				true,
				1,
				1,
				0
			],
			'undoing multiple revisions' => [
				[ '1', '2', '3', '4' ],
				'1',
				0,
				3,
				true,
				1,
				3,
				0
			],
			'undoing an intermittent revision' => [
				[
					"line 1\n\nline 2\n\nline3",
					"line 1\n\nvandalism\n\nline3",
					"line 1\n\nvandalism\n\nline3 more content"
				],
				"line 1\n\nline 2\n\nline3 more content",
				0,
				1,
				false,
				1,
				1,
				'false'
			],
			'undoing multiple intermittent revisions' => [
				[
					"line 1\n\nline 2\n\nline3",
					"line 1\n\nvandalism\n\nline3",
					"line 1\n\nmore vandalism\n\nline3",
					"line 1\n\nmore vandalism\n\nline3 content"
				],
				"line 1\n\nline 2\n\nline3 content",
				0,
				2,
				false,
				1,
				2,
				'false'
			]
		];
	}

	/**
	 * Provides test cases of undos with incomplete parameters.
	 * This should be handled well by EditPage and WikiPage.
	 * McrUndoAction just refuses to do anything.
	 *
	 * @return array[]
	 */
	public function provideIncompleteUndos() {
		return [
			'undoing a revision without undoafter param' => [
				[ '1', '2' ],
				'1',
				'false',
				1,
				true,
				1,
				1,
				0
			],
			'undoing an intermittent revision without undoafter param' => [
				[
					"line 1\n\nline 2\n\nline3",
					"line 1\n\nvandalism\n\nline3",
					"line 1\n\nvandalism\n\nline3 more content"
				],
				"line 1\n\nline 2\n\nline3 more content",
				'false',
				1,
				false,
				1,
				1,
				'false'
			]
		];
	}

	/**
	 * Test how McrUndoAction cooperates with the PageUpdater by looking at values provided
	 * by the PageSaveComplete hook.
	 *
	 * @dataProvider provideUndos
	 *
	 * @param string[] $revisions
	 * @param string $newContent
	 * @param int|string $undoafterIndex
	 * @param int|string $undoIndex
	 * @param bool $isExactRevert
	 * @param int|string $oldestRevertedRevIndex
	 * @param int|string $newestRevertedRevIndex
	 * @param int|string $originalRevIndex
	 */
	public function testMcrUndoAction(
		array $revisions,
		string $newContent,
		$undoafterIndex,
		$undoIndex,
		bool $isExactRevert,
		$oldestRevertedRevIndex,
		$newestRevertedRevIndex,
		$originalRevIndex
	) {
		$this->markTestSkippedIfNoDiff3();

		$context = RequestContext::getMain();
		$revisionIds = $this->setUpPageForTesting( $revisions );
		$article = Article::newFromTitle( Title::newFromText( self::PAGE_NAME ), $context );

		$mcrUndoAction = $this->makeNewMcrUndoAction(
			$context,
			$article,
			[
				'undoafter' => $revisionIds[$undoafterIndex],
				'undo' => $revisionIds[$undoIndex]
			]
		);
		// This should initialize the action properly.
		$mcrUndoAction->show();

		// Set the hook and submit the request
		$this->setPageSaveCompleteHook(
			$newContent,
			$revisionIds,
			$isExactRevert,
			$oldestRevertedRevIndex,
			$newestRevertedRevIndex,
			$originalRevIndex
		);
		$mcrUndoAction->onSubmit( [] );
	}

	/**
	 * Test how WikiPage cooperates with the PageUpdater by looking at values
	 * provided by the PageSaveComplete hook.
	 *
	 * @dataProvider provideUndos
	 * @dataProvider provideIncompleteUndos
	 *
	 * @param string[] $revisions
	 * @param string $newContent
	 * @param int|string $undoafterIndex
	 * @param int|string $undoIndex
	 * @param bool $isExactRevert
	 * @param int|string $oldestRevertedRevIndex
	 * @param int|string $newestRevertedRevIndex
	 * @param int|string $originalRevIndex
	 */
	public function testWikiPage(
		array $revisions,
		string $newContent,
		$undoafterIndex,
		$undoIndex,
		bool $isExactRevert,
		$oldestRevertedRevIndex,
		$newestRevertedRevIndex,
		$originalRevIndex
	) {
		$revisionIds = $this->setUpPageForTesting( $revisions );

		// Set the hook with asserts
		$this->setPageSaveCompleteHook(
			$newContent,
			$revisionIds,
			$isExactRevert,
			$oldestRevertedRevIndex,
			$newestRevertedRevIndex,
			$originalRevIndex
		);

		$wikiPage = new WikiPage( Title::newFromText( self::PAGE_NAME ) );
		$wikiPage->doEditContent(
			new WikitextContent( $newContent ),
			'',
			0,
			$revisionIds[$undoafterIndex],
			$this->getTestSysop()->getUser(),
			null,
			[],
			$revisionIds[$undoIndex]
		);
	}

	/**
	 * Test how EditPage and WikiPage work together and with the PageUpdater by looking
	 * at values provided by the PageSaveComplete hook.
	 *
	 * @dataProvider provideUndos
	 * @dataProvider provideIncompleteUndos
	 *
	 * @param string[] $revisions
	 * @param string $newContent
	 * @param int|string $undoafterIndex
	 * @param int|string $undoIndex
	 * @param bool $isExactRevert
	 * @param int|string $oldestRevertedRevIndex
	 * @param int|string $newestRevertedRevIndex
	 * @param int|string $originalRevIndex
	 */
	public function testEditPage(
		array $revisions,
		string $newContent,
		$undoafterIndex,
		$undoIndex,
		bool $isExactRevert,
		$oldestRevertedRevIndex,
		$newestRevertedRevIndex,
		$originalRevIndex
	) {
		$this->markTestSkippedIfNoDiff3();

		$revisionIds = $this->setUpPageForTesting( $revisions );
		$context = RequestContext::getMain();
		$article = Article::newFromTitle( Title::newFromText( self::PAGE_NAME ), $context );

		// Set the hook with asserts
		$this->setPageSaveCompleteHook(
			$newContent,
			$revisionIds,
			$isExactRevert,
			$oldestRevertedRevIndex,
			$newestRevertedRevIndex,
			$originalRevIndex
		);

		$request = new FauxRequest(
			[
				// We kind of let EditPage cheat here by providing the content of the page
				// after the undo, but automatic conflict resolution is not the point of
				// this test anyway.
				'wpTextbox1' => $newContent,
				'wpEditToken' => $this->getTestSysop()->getUser()->getEditToken(),
				// These two parameters are the important ones here
				'wpUndidRevision' => $revisionIds[$undoIndex],
				'wpUndoAfter' => $revisionIds[$undoafterIndex],
				'wpStarttime' => wfTimestampNow(),
				'wpUnicodeCheck' => EditPage::UNICODE_CHECK,
				'model' => CONTENT_MODEL_WIKITEXT,
				'format' => CONTENT_FORMAT_WIKITEXT,
			],
			true
		);

		$editPage = new EditPage( $article );
		$editPage->importFormData( $request );
		$editPage->internalAttemptSave( $result, false );
	}
}
