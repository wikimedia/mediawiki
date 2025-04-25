<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\WikitextContent;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPage;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\EditResultBuilder;
use MediaWikiIntegrationTestCase;
use MockTitleTrait;
use Wikimedia\Rdbms\IDatabase;

/**
 * @covers \MediaWiki\Storage\EditResultBuilder
 * @group Database
 * @see EditResultBuilderTest for non-DB tests
 */
class EditResultBuilderDbTest extends MediaWikiIntegrationTestCase {
	use MockTitleTrait;

	private const PAGE_NAME = 'ManualRevertTestPage';
	private const CONTENT_A = 'Aaa.';
	private const CONTENT_B = 'Bbb.';
	private const CONTENT_C = 'Ccc.';

	/** @var WikiPage */
	private $wikiPage;

	/** @var RevisionRecord[] */
	private $revisions;

	/**
	 * We track the top revision of the test page on our own to avoid having to update the
	 * page table in the DB.
	 *
	 * @var RevisionRecord
	 */
	private $latestTestRevision = null;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var IDatabase */
	private $dbw;

	protected function setUp(): void {
		parent::setUp();

		$services = $this->getServiceContainer();
		$this->revisionStore = $services->getRevisionStore();
		$this->dbw = $this->getDb();

		$this->wikiPage = $this->getExistingTestPage( self::PAGE_NAME );
		$this->revisions = [];
		$this->revisions['C1'] = $this->insertRevisionToTestPage(
			self::CONTENT_C,
			'20050101210030'
		);
		$this->revisions['A1'] = $this->insertRevisionToTestPage(
			self::CONTENT_A,
			'20050101210037'
		);
		$this->revisions['B1'] = $this->insertRevisionToTestPage(
			self::CONTENT_B,
			'20050101210038'
		);
		$this->revisions['C2'] = $this->insertRevisionToTestPage(
			self::CONTENT_C,
			'20050101210039'
		);
		$this->revisions['A2'] = $this->insertRevisionToTestPage(
			self::CONTENT_A,
			'20050101210040'
		);
		$this->revisions['A3'] = $this->insertRevisionToTestPage(
			self::CONTENT_A,
			'20050101210040' // same timestamp to try to confuse the query
		);
		$this->revisions['A4'] = $this->insertRevisionToTestPage(
			self::CONTENT_A,
			'20050101210040'
		);
		$this->revisions['B2'] = $this->insertRevisionToTestPage(
			self::CONTENT_B,
			'20050101210041'
		);
	}

	private function getLatestTestRevision(): RevisionRecord {
		return $this->latestTestRevision ??
			$this->revisionStore->getRevisionByPageId( $this->wikiPage->getId() );
	}

	/**
	 * Inserts a new revision of the test page to the DB with specified content.
	 *
	 * We do not use MediaWikiIntegrationTestCase::editPage() on purpose, it can lead to all
	 * kinds of issues, the most significant being that it ultimately calls the code we wish
	 * to test here.
	 *
	 * @param string $content
	 *
	 * @param string $timestamp
	 *
	 * @return RevisionRecord
	 */
	private function insertRevisionToTestPage(
		string $content,
		string $timestamp
	): RevisionRecord {
		$revisionRecord = $this->getNewRevisionForTestPage( $content );
		$revisionRecord->setUser( $this->getTestUser()->getUser() );
		$revisionRecord->setTimestamp( $timestamp );
		$revisionRecord->setComment( CommentStoreComment::newUnsavedComment( '' ) );

		$this->latestTestRevision = $this->revisionStore->insertRevisionOn(
			$revisionRecord,
			$this->dbw
		);
		return $this->latestTestRevision;
	}

	/**
	 * Returns a next in sequence revision of the test page with specified content.
	 *
	 * @param string $content
	 *
	 * @return MutableRevisionRecord
	 */
	private function getNewRevisionForTestPage(
		string $content
	): MutableRevisionRecord {
		$parentRevision = $this->getLatestTestRevision();

		$revision = new MutableRevisionRecord( $this->wikiPage->getTitle() );
		$revision->setParentId( $parentRevision->getId() );
		$revision->setPageId( $this->wikiPage->getId() );
		$revision->setContent(
			SlotRecord::MAIN,
			new WikitextContent( $content )
		);

		return $revision;
	}

	public static function provideManualReverts(): array {
		return [
			'reverting a single edit' => [
				self::CONTENT_A,
				'A4',
				'B2',
				'B2'
			],
			'reverting multiple edits' => [
				self::CONTENT_C,
				'C2',
				'A2',
				'B2'
			]
		];
	}

	/**
	 * @dataProvider provideManualReverts
	 * @covers \MediaWiki\Storage\EditResultBuilder::detectManualRevert
	 *
	 * @param string $content
	 * @param string $expectedOriginalRevKey
	 * @param string $expectedOldestRevertedRevKey
	 * @param string $expectedNewestRevertedRevKey
	 */
	public function testManualRevert(
		string $content,
		string $expectedOriginalRevKey,
		string $expectedOldestRevertedRevKey,
		string $expectedNewestRevertedRevKey
	) {
		$erb = $this->getEditResultBuilder();
		$newRevision = $this->getNewRevisionForTestPage( $content );
		// we will fool the EditResultBuilder into thinking this is a saved revision
		$newRevision->setId( 12345 );
		$erb->setRevisionRecord( $newRevision );

		$er = $erb->buildEditResult();

		// first some basic tests we can do without revision magic
		$this->assertTrue(
			$er->isRevert(),
			'EditResult::isRevert()'
		);
		$this->assertTrue(
			$er->isExactRevert(),
			'EditResult::isExactRevert()'
		);
		$this->assertSame(
			EditResult::REVERT_MANUAL,
			$er->getRevertMethod(),
			'EditResult::getRevertMethod()'
		);
		$this->assertNotFalse(
			$er->getOriginalRevisionId(),
			'EditResult::getOriginalRevisionId()'
		);
		$this->assertNotNull(
			$er->getOldestRevertedRevisionId(),
			'EditResult::getOldestRevertedRevisionId()'
		);
		$this->assertNotNull(
			$er->getNewestRevertedRevisionId(),
			'EditResult::getNewestRevertedRevisionId()'
		);
		$this->assertArrayEquals(
			[ 'mw-manual-revert' ],
			$er->getRevertTags(),
			'EditResult::getRevertTags()'
		);

		// test the original revision referenced by this EditResult
		$originalRev = $this->revisionStore->getRevisionById(
			$er->getOriginalRevisionId()
		);
		$this->assertSame(
			$newRevision->getSha1(),
			$originalRev->getSha1(),
			"original revision's SHA1 matches new revision's SHA1"
		);
		$expectedOriginalRev = $this->revisions[$expectedOriginalRevKey];
		$this->assertSame(
			$expectedOriginalRev->getId(),
			$originalRev->getId(),
			"original revision's ID"
		);

		// test the oldest reverted revision
		$oldestRevertedRev = $this->revisionStore->getRevisionById(
			$er->getOldestRevertedRevisionId()
		);
		$expectedOldestRevertedRev = $this->revisions[$expectedOldestRevertedRevKey];
		$this->assertSame(
			$expectedOldestRevertedRev->getId(),
			$oldestRevertedRev->getId(),
			"oldest reverted revision's ID"
		);

		// test the newest reverted revision
		$newestRevertedRev = $this->revisionStore->getRevisionById(
			$er->getNewestRevertedRevisionId()
		);
		$expectedNewestRevertedRev = $this->revisions[$expectedNewestRevertedRevKey];
		$this->assertSame(
			$expectedNewestRevertedRev->getId(),
			$newestRevertedRev->getId(),
			"newest reverted revision's ID"
		);
	}

	public static function provideNotManualReverts(): array {
		return [
			'edit not changing anything' => [
				self::CONTENT_B,
				15
			],
			'revert outside search radius' => [
				self::CONTENT_C,
				3
			],
			'normal edit' => [
				'Some text.',
				15
			]
		];
	}

	/**
	 * @dataProvider provideNotManualReverts
	 * @covers \MediaWiki\Storage\EditResultBuilder::detectManualRevert
	 *
	 * @param string $content
	 * @param int $searchRadius
	 */
	public function testNotManualRevert(
		string $content,
		int $searchRadius
	) {
		$erb = $this->getEditResultBuilder( $searchRadius );
		$parentRevision = $this->getLatestTestRevision();
		$newRevision = $this->getNewRevisionForTestPage( $content );
		// we will fool the EditResultBuilder into thinking this is a saved revision
		$newRevision->setId( 12345 );
		$erb->setRevisionRecord( $newRevision );
		// emulate WikiPage's behaviour for null edits
		if ( $newRevision->getSha1() === $parentRevision->getSha1() ) {
			$erb->setOriginalRevision( $parentRevision );
		}

		$er = $erb->buildEditResult();

		$this->assertFalse( $er->isRevert(), 'EditResult::isRevert()' );
		$this->assertFalse( $er->isExactRevert(), 'EditResult::isExactRevert()' );
		$this->assertNull( $er->getRevertMethod(), 'EditResult::getRevertMethod()' );
		$this->assertNull(
			$er->getOldestRevertedRevisionId(),
			'EditResult::getOldestRevertedRevisionId()'
		);
		$this->assertNull(
			$er->getNewestRevertedRevisionId(),
			'EditResult::getNewestRevertedRevisionId()'
		);
		$this->assertArrayEquals( [], $er->getRevertTags(), 'EditResult::getRevertTags()' );
	}

	/**
	 * Convenience function for creating a new EditResultBuilder object.
	 *
	 * @param int $manualRevertSearchRadius
	 *
	 * @return EditResultBuilder
	 */
	private function getEditResultBuilder( int $manualRevertSearchRadius = 15 ) {
		$options = new ServiceOptions(
			EditResultBuilder::CONSTRUCTOR_OPTIONS,
			[ MainConfigNames::ManualRevertSearchRadius => $manualRevertSearchRadius ]
		);

		return new EditResultBuilder(
			$this->getServiceContainer()->getRevisionStore(),
			$this->getServiceContainer()->getChangeTagsStore()->listSoftwareDefinedTags(),
			$options
		);
	}
}
