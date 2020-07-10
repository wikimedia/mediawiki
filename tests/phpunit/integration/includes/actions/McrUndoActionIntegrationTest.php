<?php

namespace MediaWiki\Tests;

use Article;
use FauxRequest;
use McrUndoAction;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Storage\EditResult;
use MediaWikiIntegrationTestCase;
use RequestContext;
use Title;
use User;
use WikiPage;

/**
 * TODO: This should also test edits with multiple slots.
 *
 * @covers McrUndoAction
 *
 * @group Action
 * @group Database
 * @group medium
 */
class McrUndoActionIntegrationTest extends MediaWikiIntegrationTestCase {

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

	public function provideUndos() {
		return [
			'undoing a single revision' => [
				[ '1', '2' ],
				0,
				1,
				true,
				1,
				1,
				0
			],
			'undoing multiple revisions' => [
				[ '1', '2', '3', '4' ],
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
	 * Test how McrUndoAction cooperates with the PageUpdater by looking at values provided
	 * by the PageSaveComplete hook.
	 *
	 * @dataProvider provideUndos
	 *
	 * @param array $revisions
	 * @param int $undoafterIndex
	 * @param int $undoIndex
	 * @param bool $isExactRevert
	 * @param int $oldestRevertedRevIndex
	 * @param int $newestRevertedRevIndex
	 * @param int|string $originalRevIndex
	 */
	public function testPageSaveCompleteHook(
		array $revisions,
		int $undoafterIndex,
		int $undoIndex,
		bool $isExactRevert,
		int $oldestRevertedRevIndex,
		int $newestRevertedRevIndex,
		$originalRevIndex
	) {
		$context = RequestContext::getMain();
		$this->getExistingTestPage( self::PAGE_NAME );
		$revisionIds = [];
		foreach ( $revisions as $revisionContent ) {
			$revisionIds[] = $this->editPage( self::PAGE_NAME, $revisionContent )
				->value['revision-record']->getId();
		}
		$revisionIds['false'] = false;
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
			}
		);

		// submit the request
		$mcrUndoAction->onSubmit( [] );
	}
}
