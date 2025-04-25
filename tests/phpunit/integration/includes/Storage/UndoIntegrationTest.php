<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Actions\McrUndoAction;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\EditPage\EditPage;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\Article;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Page\WikiPage;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\Tests\ExpectCallbackTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * Integration tests for undos.
 * TODO: This should also test edits with multiple slots.
 *
 * @covers \MediaWiki\Actions\McrUndoAction
 * @covers \MediaWiki\Page\WikiPage
 * @covers \MediaWiki\EditPage\EditPage
 *
 * @group Database
 * @group medium
 */
class UndoIntegrationTest extends MediaWikiIntegrationTestCase {
	use ExpectCallbackTrait;

	private const PAGE_NAME = 'McrUndoTestPage';

	/**
	 * Creates a new McrUndoAction object for testing.
	 *
	 * @param IContextSource $context
	 * @param Article $article
	 * @param array $params POST/GET parameters passed to the action on submit
	 *
	 * @return McrUndoAction
	 */
	private function makeNewMcrUndoAction(
		IContextSource $context,
		Article $article,
		array $params
	): McrUndoAction {
		$request = new FauxRequest( $params );
		$request->setVal( 'wpSave', '' );
		$context->setRequest( $request );

		$outputPage = $this->createMock( OutputPage::class );
		$context->setOutput( $outputPage );
		$context->setUser( $this->getTestSysop()->getUser() );

		$services = $this->getServiceContainer();
		$revisionRenderer = $services->getRevisionRenderer();
		$revisionLookup = $services->getRevisionLookup();
		$readOnlyMode = $services->getReadOnlyMode();
		$commentFormatter = $services->getCommentFormatter();
		$config = $services->getMainConfig();
		return new class(
			$article,
			$context,
			$readOnlyMode,
			$revisionLookup,
			$revisionRenderer,
			$commentFormatter,
			$config
		) extends McrUndoAction {
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
	private function setUpPageForTesting( array $revisions ): array {
		$this->getExistingTestPage( self::PAGE_NAME );
		$revisionIds = [];
		foreach ( $revisions as $revisionContent ) {
			$revisionIds[] = $this->editPage( self::PAGE_NAME, $revisionContent )
				->value['revision-record']->getId();
		}
		$revisionIds['false'] = false;
		return $revisionIds;
	}

	private function assertEditResult(
		EditResult $editResult,
		bool $isExactRevert,
		array $revisionIds,
		$oldestRevertedRevIndex,
		$newestRevertedRevIndex,
		$originalRevIndex,
		RevisionRecord $revisionRecord,
		string $newContent
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
			'EditResult::getRevertTags()' );
		$this->assertSame(
			$isExactRevert,
			$editResult->isExactRevert(),
			'EditResult::isExactRevert()'
		);
		$this->assertSame(
			$revisionIds[ $oldestRevertedRevIndex ],
			$editResult->getOldestRevertedRevisionId(),
			'EditResult::getOldestRevertedRevisionId()'
		);
		$this->assertSame(
			$revisionIds[ $newestRevertedRevIndex ],
			$editResult->getNewestRevertedRevisionId(),
			'EditResult::getNewestRevertedRevisionId()'
		);
		$this->assertSame(
			$revisionIds[ $originalRevIndex ],
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

	private function expectPageSaveCompleteHook(
		string $newContent,
		array $revisionIds,
		bool $isExactRevert,
		$oldestRevertedRevIndex,
		$newestRevertedRevIndex,
		$originalRevIndex
	) {
		// set up a temporary hook with asserts
		$this->expectHook(
			'PageSaveComplete', 1,
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
				$this->assertEditResult(
					$editResult,
					$isExactRevert,
					$revisionIds,
					$oldestRevertedRevIndex,
					$newestRevertedRevIndex,
					$originalRevIndex,
					$revisionRecord,
					$newContent
				);
			}
		);
	}

	private function expectPageRevisionUpdatedEvent(
		string $newContent,
		array $revisionIds,
		bool $isExactRevert,
		$oldestRevertedRevIndex,
		$newestRevertedRevIndex,
		$originalRevIndex
	) {
		// set up a temporary hook with asserts
		$this->expectDomainEvent(
			PageRevisionUpdatedEvent::TYPE, 1,
			function ( PageRevisionUpdatedEvent $event ) use (
				$newContent,
				$revisionIds,
				$isExactRevert,
				$oldestRevertedRevIndex,
				$newestRevertedRevIndex,
				$originalRevIndex
			) {
				$this->assertTrue( $event->isRevert(), 'isRevert()' );
				$this->assertTrue(
					$event->hasCause( PageRevisionUpdatedEvent::CAUSE_UNDO ),
					'CAUSE_UNDO'
				);

				$editResult = $event->getEditResult();
				$this->assertNotNull( $editResult, 'getEditResult' );

				$this->assertEditResult(
					$editResult,
					$isExactRevert,
					$revisionIds,
					$oldestRevertedRevIndex,
					$newestRevertedRevIndex,
					$originalRevIndex,
					$event->getLatestRevisionAfter(),
					$newContent
				);
			}
		);
	}

	/**
	 * Provides test cases for well-formed undos.
	 *
	 * @return array[]
	 */
	public static function provideUndos() {
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
	public static function provideIncompleteUndos() {
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

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $this->getTestUser()->getUser() );
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
		$this->expectPageSaveCompleteHook(
			$newContent,
			$revisionIds,
			$isExactRevert,
			$oldestRevertedRevIndex,
			$newestRevertedRevIndex,
			$originalRevIndex
		);
		$this->expectPageRevisionUpdatedEvent(
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
		$this->expectPageSaveCompleteHook(
			$newContent,
			$revisionIds,
			$isExactRevert,
			$oldestRevertedRevIndex,
			$newestRevertedRevIndex,
			$originalRevIndex
		);
		$this->expectPageRevisionUpdatedEvent(
			$newContent,
			$revisionIds,
			$isExactRevert,
			$oldestRevertedRevIndex,
			$newestRevertedRevIndex,
			$originalRevIndex
		);

		$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( self::PAGE_NAME ) );
		$wikiPage->doUserEditContent(
			new WikitextContent( $newContent ),
			$this->getTestSysop()->getUser(),
			'',
			0,
			$revisionIds[$undoafterIndex],
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
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $this->getTestUser()->getUser() );
		$article = Article::newFromTitle( Title::newFromText( self::PAGE_NAME ), $context );

		// Set the hook with asserts
		$this->expectPageSaveCompleteHook(
			$newContent,
			$revisionIds,
			$isExactRevert,
			$oldestRevertedRevIndex,
			$newestRevertedRevIndex,
			$originalRevIndex
		);
		$this->expectPageRevisionUpdatedEvent(
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
		$editPage->attemptSave( $result );
	}

	/**
	 * Test the case where the user undoes some edits, but applies additional changes before
	 * saving. EditPage should detect that and not mark such an edit as a revert.
	 */
	public function testDirtyUndo() {
		$revisionIds = $this->setUpPageForTesting( [
			"line 1\n\nline 2\n\nline3",
			"line 1\n\nvandalism\n\nline3",
			"line 1\n\nvandalism\n\nline3 more content"
		] );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $this->getTestUser()->getUser() );
		$article = Article::newFromTitle( Title::newFromText( self::PAGE_NAME ), $context );

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
			) {
				// Just ensuring that the edit was not marked as a revert should be enough
				$this->assertFalse(
					$editResult->isRevert(),
					'EditResult::isRevert()'
				);
			}
		);

		$request = new FauxRequest(
			[
				// We emulate the user applying additional changes on top of the undo.
				'wpTextbox1' => "line 1\n\nline 2\n\nline3 more content\n\neven more",
				'wpEditToken' => $this->getTestSysop()->getUser()->getEditToken(),
				'wpUndidRevision' => $revisionIds[1],
				'wpUndoAfter' => $revisionIds[0],
				'wpStarttime' => wfTimestampNow(),
				'wpUnicodeCheck' => EditPage::UNICODE_CHECK,
				'model' => CONTENT_MODEL_WIKITEXT,
				'format' => CONTENT_FORMAT_WIKITEXT,
			],
			true
		);

		$editPage = new EditPage( $article );
		$editPage->importFormData( $request );
		$editPage->attemptSave( $result );
	}

	/**
	 * Test whether EditPage correctly handles situations where an undo is impossible.
	 * Ensures T262463 is fixed.
	 */
	public function testImpossibleUndo() {
		$revisionIds = $this->setUpPageForTesting( [
			"line 1\n\nline 2\n\nline3",
			"line 1\n\nvandalism\n\nline3",
			"line 1\n\nvandalism good content\n\nline3 more content"
		] );

		$context = RequestContext::getMain();
		$article = Article::newFromTitle( Title::newFromText( self::PAGE_NAME ), $context );

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
			) {
				$this->assertFalse(
					$editResult->isRevert(),
					'EditResult::isRevert()'
				);
				$this->assertTrue(
					$editResult->isNullEdit(),
					'EditResult::isNullEdit()'
				);
			}
		);

		$request = new FauxRequest(
			[
				// We leave the "top" content in the textbox, as the undo should have failed
				'wpTextbox1' => "line 1\n\nvandalism good content\n\nline3 more content",
				'wpEditToken' => $this->getTestSysop()->getUser()->getEditToken(),
				'wpUndidRevision' => $revisionIds[1],
				'wpUndoAfter' => $revisionIds[0],
				'wpStarttime' => wfTimestampNow(),
				'wpUnicodeCheck' => EditPage::UNICODE_CHECK,
				'model' => CONTENT_MODEL_WIKITEXT,
				'format' => CONTENT_FORMAT_WIKITEXT,
			],
			true
		);

		$editPage = new EditPage( $article );
		$editPage->importFormData( $request );
		$editPage->attemptSave( $result );
	}
}
