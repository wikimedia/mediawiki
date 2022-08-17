<?php

namespace MediaWiki\Tests\Storage;

use DummyContentForTesting;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\EditResultBuilder;
use MediaWiki\Storage\PageUpdateException;
use MediaWikiUnitTestCase;
use MockTitleTrait;

/**
 * @covers \MediaWiki\Storage\EditResultBuilder
 * @covers \MediaWiki\Storage\EditResult
 * @see EditResultBuilderDbTest for integration tests with the database
 */
class EditResultBuilderTest extends MediaWikiUnitTestCase {
	use MockTitleTrait;

	/**
	 * @covers \MediaWiki\Storage\EditResultBuilder::buildEditResult
	 */
	public function testBuilderThrowsExceptionOnMissingRevision() {
		$erb = $this->getNewEditResultBuilder();

		$this->expectException( PageUpdateException::class );
		$erb->buildEditResult();
	}

	/**
	 * @covers \MediaWiki\Storage\EditResultBuilder
	 */
	public function testIsNewUnset() {
		$erb = $this->getNewEditResultBuilder();
		$erb->setRevisionRecord( $this->getDummyRevision() );
		$er = $erb->buildEditResult();

		$this->assertFalse( $er->isNew(), 'EditResult::isNew()' );
	}

	public function provideSetIsNew() {
		return [
			'not a new page' => [ false ],
			'a new page' => [ true ]
		];
	}

	/**
	 * @dataProvider provideSetIsNew
	 * @covers \MediaWiki\Storage\EditResultBuilder
	 * @param bool $isNew
	 */
	public function testSetIsNew( bool $isNew ) {
		$erb = $this->getNewEditResultBuilder();
		$erb->setIsNew( $isNew );
		$erb->setRevisionRecord( $this->getDummyRevision() );
		$er = $erb->buildEditResult();

		$this->assertSame( $isNew, $er->isNew(), 'EditResult::isNew()' );
	}

	/**
	 * Tests a normal edit to the page
	 * @covers \MediaWiki\Storage\EditResult
	 * @covers \MediaWiki\Storage\EditResultBuilder
	 */
	public function testEditNotARevert() {
		$erb = $this->getNewEditResultBuilder();
		$erb->setRevisionRecord( $this->getDummyRevision() );
		$er = $erb->buildEditResult();

		$this->assertFalse( $er->isNew(), 'EditResult::isNew()' );
		$this->assertFalse( $er->isNullEdit(), 'EditResult::isNullEdit()' );
		$this->assertFalse( $er->getOriginalRevisionId(),
			'EditResult::getOriginalRevisionId()' );
		$this->assertFalse( $er->isRevert(), 'EditResult::isRevert()' );
		$this->assertFalse( $er->isExactRevert(), 'EditResult::isExactRevert()' );
		$this->assertNull( $er->getRevertMethod(), 'EditResult::getRevertMethod()' );
		$this->assertNull( $er->getOldestRevertedRevisionId(),
			'EditResult::getOldestRevertedRevisionId()' );
		$this->assertNull( $er->getNewestRevertedRevisionId(),
			'EditResult::getNewestRevertedRevisionId()' );
		$this->assertSame( 0, $er->getUndidRevId(), 'EditResult::getUndidRevId' );
		$this->assertArrayEquals( [], $er->getRevertTags(), 'EditResult::getRevertTags' );
	}

	/**
	 * Tests the case when the new edit doesn't actually change anything on the page,
	 * i.e. is a null edit.
	 * @covers \MediaWiki\Storage\EditResult
	 * @covers \MediaWiki\Storage\EditResultBuilder
	 */
	public function testNullEdit() {
		$originalRevision = $this->getExistingRevision( 5 );
		$erb = $this->getNewEditResultBuilder( $originalRevision );
		$newRevision = $this->getExistingRevision( 6 );
		$newRevision->setParentId( 5 );

		$erb->setOriginalRevision( $originalRevision );
		$erb->setRevisionRecord( $newRevision );
		$er = $erb->buildEditResult();

		$this->assertFalse( $er->isNew(), 'EditResult::isNew()' );
		$this->assertTrue( $er->isNullEdit(), 'EditResult::isNullEdit()' );
		$this->assertSame( $originalRevision->getId(), $er->getOriginalRevisionId(),
			'EditResult::getOriginalRevisionId()' );
		$this->assertFalse( $er->isRevert(), 'EditResult::isRevert()' );
		$this->assertFalse( $er->isExactRevert(), 'EditResult::isExactRevert()' );
		$this->assertNull( $er->getRevertMethod(), 'EditResult::getRevertMethod()' );
		$this->assertNull( $er->getOldestRevertedRevisionId(),
			'EditResult::getOldestRevertedRevisionId()' );
		$this->assertNull( $er->getNewestRevertedRevisionId(),
			'EditResult::getNewestRevertedRevisionId()' );
		$this->assertSame( 0, $er->getUndidRevId(), 'EditResult::getUndidRevId' );
		$this->assertArrayEquals( [], $er->getRevertTags(), 'EditResult::getRevertTags' );
	}

	public function provideEnabledSoftwareTagsForRollback(): array {
		return [
			"all change tags enabled" => [
				$this->getSoftwareTags(),
				[ "mw-rollback" ]
			],
			"no change tags enabled" => [
				[],
				[]
			]
		];
	}

	/**
	 * Test the case where the edit restored the page exactly to a previous state.
	 *
	 * @covers \MediaWiki\Storage\EditResult
	 * @covers \MediaWiki\Storage\EditResultBuilder
	 * @dataProvider provideEnabledSoftwareTagsForRollback
	 *
	 * @param string[] $changeTags
	 * @param string[] $expectedRevertTags
	 */
	public function testRollback( array $changeTags, array $expectedRevertTags ) {
		$originalRevision = $this->getExistingRevision( 5 );
		$erb = $this->getNewEditResultBuilder( $originalRevision, $changeTags );
		$newRevision = $this->getExistingRevision( 225 );
		// We change the parent id to something different, so it's not treated as a null edit
		$newRevision->setParentId( 125 );

		$erb->setOriginalRevision( $originalRevision );
		$erb->setRevisionRecord( $newRevision );
		// We are bluffing here, those revision ids don't exist.
		// EditResult is as dumb as possible, it doesn't check that.
		$erb->markAsRevert(
			EditResult::REVERT_ROLLBACK,
			125,
			122
		);
		$er = $erb->buildEditResult();

		$this->assertFalse( $er->isNew(), 'EditResult::isNew()' );
		$this->assertFalse( $er->isNullEdit(), 'EditResult::isNullEdit()' );
		$this->assertSame( $originalRevision->getId(), $er->getOriginalRevisionId(),
			'EditResult::getOriginalRevisionId()' );
		$this->assertTrue( $er->isRevert(), 'EditResult::isRevert()' );
		$this->assertTrue( $er->isExactRevert(), 'EditResult::isExactRevert()' );
		$this->assertSame( EditResult::REVERT_ROLLBACK, $er->getRevertMethod(),
			'EditResult::getRevertMethod()' );
		$this->assertSame( 123, $er->getOldestRevertedRevisionId(),
			'EditResult::getOldestRevertedRevisionId()' );
		$this->assertSame( 125, $er->getNewestRevertedRevisionId(),
			'EditResult::getNewestRevertedRevisionId()' );
		$this->assertSame( 0, $er->getUndidRevId(), 'EditResult::getUndidRevId' );
		$this->assertArrayEquals( $expectedRevertTags, $er->getRevertTags(),
			'EditResult::getRevertTags' );
	}

	public function provideEnabledSoftwareTagsForUndo(): array {
		return [
			"all change tags enabled" => [
				$this->getSoftwareTags(),
				[ "mw-undo" ]
			],
			"no change tags enabled" => [
				[],
				[]
			]
		];
	}

	/**
	 * Test the case where the edit was an undo
	 *
	 * @covers \MediaWiki\Storage\EditResult
	 * @covers \MediaWiki\Storage\EditResultBuilder
	 * @dataProvider provideEnabledSoftwareTagsForUndo
	 *
	 * @param string[] $changeTags
	 * @param string[] $expectedRevertTags
	 */
	public function testUndo( array $changeTags, array $expectedRevertTags ) {
		$originalRevision = $this->getExistingRevision( 5 );
		$erb = $this->getNewEditResultBuilder( $originalRevision, $changeTags );
		$newRevision = $this->getExistingRevision( 225 );
		// We change the parent id to something different, so it's not treated as a null edit
		$newRevision->setParentId( 124 );

		$erb->setOriginalRevision( $originalRevision );
		$erb->setRevisionRecord( $newRevision );
		$erb->markAsRevert( EditResult::REVERT_UNDO, 124 );
		$er = $erb->buildEditResult();

		$this->assertFalse( $er->isNew(), 'EditResult::isNew()' );
		$this->assertFalse( $er->isNullEdit(), 'EditResult::isNullEdit()' );
		$this->assertSame( $originalRevision->getId(), $er->getOriginalRevisionId(),
			'EditResult::getOriginalRevisionId()' );
		$this->assertTrue( $er->isRevert(), 'EditResult::isRevert()' );
		$this->assertTrue( $er->isExactRevert(), 'EditResult::isExactRevert()' );
		$this->assertSame( EditResult::REVERT_UNDO, $er->getRevertMethod(),
			'EditResult::getRevertMethod()' );
		$this->assertSame( 124, $er->getOldestRevertedRevisionId(),
			'EditResult::getOldestRevertedRevisionId()' );
		$this->assertSame( 124, $er->getNewestRevertedRevisionId(),
			'EditResult::getNewestRevertedRevisionId()' );
		$this->assertSame( 124, $er->getUndidRevId(), 'EditResult::getUndidRevId' );
		$this->assertArrayEquals( $expectedRevertTags, $er->getRevertTags(),
			'EditResult::getRevertTags' );
	}

	/**
	 * Test the case where setRevert() is called, but nothing was really reverted
	 *
	 * @covers \MediaWiki\Storage\EditResult
	 * @covers \MediaWiki\Storage\EditResultBuilder
	 */
	public function testIgnoreEmptyRevert() {
		$erb = $this->getNewEditResultBuilder();
		$newRevision = $this->getDummyRevision();

		$erb->setRevisionRecord( $newRevision );
		$erb->markAsRevert( EditResult::REVERT_UNDO, 0 );
		$er = $erb->buildEditResult();

		$this->assertFalse( $er->isNew(), 'EditResult::isNew()' );
		$this->assertFalse( $er->isNullEdit(), 'EditResult::isNullEdit()' );
		$this->assertFalse( $er->getOriginalRevisionId(), 'EditResult::getOriginalRevisionId()' );
		$this->assertFalse( $er->isRevert(), 'EditResult::isRevert()' );
		$this->assertFalse( $er->isExactRevert(), 'EditResult::isExactRevert()' );
		$this->assertNull( $er->getRevertMethod(), 'EditResult::getRevertMethod()' );
		$this->assertNull( $er->getOldestRevertedRevisionId(),
			'EditResult::getOldestRevertedRevisionId()' );
		$this->assertNull( $er->getNewestRevertedRevisionId(),
			'EditResult::getNewestRevertedRevisionId()' );
		$this->assertSame( 0, $er->getUndidRevId(), 'EditResult::getUndidRevId' );
		$this->assertArrayEquals( [], $er->getRevertTags(), 'EditResult::getRevertTags' );
	}

	/**
	 * Test the case where setRevert() is properly called, but the original revision was not set
	 *
	 * @covers \MediaWiki\Storage\EditResult
	 * @covers \MediaWiki\Storage\EditResultBuilder
	 */
	public function testRevertWithoutOriginalRevision() {
		$erb = $this->getNewEditResultBuilder(
			null,
			$this->getSoftwareTags()
		);
		$newRevision = $this->getDummyRevision();

		$erb->setRevisionRecord( $newRevision );
		$erb->markAsRevert( EditResult::REVERT_UNDO, 123 );
		$er = $erb->buildEditResult();

		$this->assertFalse( $er->isNew(), 'EditResult::isNew()' );
		$this->assertFalse( $er->isNullEdit(), 'EditResult::isNullEdit()' );
		$this->assertFalse( $er->getOriginalRevisionId(), 'EditResult::getOriginalRevisionId()' );
		$this->assertTrue( $er->isRevert(), 'EditResult::isRevert()' );
		$this->assertFalse( $er->isExactRevert(), 'EditResult::isExactRevert()' );
		$this->assertSame( EditResult::REVERT_UNDO, $er->getRevertMethod(),
			'EditResult::getRevertMethod()' );
		$this->assertSame( 123, $er->getOldestRevertedRevisionId(),
			'EditResult::getOldestRevertedRevisionId()' );
		$this->assertSame( 123, $er->getNewestRevertedRevisionId(),
			'EditResult::getNewestRevertedRevisionId()' );
		$this->assertSame( 123, $er->getUndidRevId(), 'EditResult::getUndidRevId' );
		$this->assertArrayEquals( [ 'mw-undo' ], $er->getRevertTags(),
			'EditResult::getRevertTags' );
	}

	/**
	 * This case satisfies all criteria to be eligible for manual revert detection, but the
	 * feature is disabled. Any attempt to call the LoadBalancer will fail this test.
	 *
	 * @covers \MediaWiki\Storage\EditResultBuilder::buildEditResult
	 */
	public function testManualRevertDetectionDisabled() {
		$erb = $this->getNewEditResultBuilder(
			null,
			$this->getSoftwareTags(),
			0 // set the search radius to 0 to disable the feature entirely
		);
		$newRevision = $this->getDummyRevision();
		$newRevision->setParentId( 125 );

		$erb->setRevisionRecord( $newRevision );
		$er = $erb->buildEditResult();

		$this->assertFalse( $er->getOriginalRevisionId(), 'EditResult::getOriginalRevisionId()' );
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
		$this->assertSame( 0, $er->getUndidRevId(), 'EditResult::getUndidRevId' );
		$this->assertArrayEquals( [], $er->getRevertTags(), 'EditResult::getRevertTags' );
	}

	/**
	 * Returns an empty RevisionRecord
	 *
	 * @return MutableRevisionRecord
	 */
	private function getDummyRevision(): MutableRevisionRecord {
		return new MutableRevisionRecord(
			$this->makeMockTitle( 'Dummy' )
		);
	}

	/**
	 * Returns a RevisionRecord that pretends to have an ID and a page ID.
	 *
	 * @param int $id
	 * @return MutableRevisionRecord
	 */
	private function getExistingRevision( int $id = 1234 ): MutableRevisionRecord {
		$revisionRecord = $this->getDummyRevision();
		$revisionRecord->setId( $id );
		$revisionRecord->setPageId( 5 );
		$revisionRecord->setContent( SlotRecord::MAIN, new DummyContentForTesting( 'Testing' ) );
		return $revisionRecord;
	}

	/**
	 * Convenience function for creating a new EditResultBuilder object.
	 *
	 * @param RevisionRecord|null $originalRevisionRecord RevisionRecord that should be returned
	 *        by RevisionStore::getRevisionById.
	 * @param string[] $changeTags
	 * @param int $manualRevertSearchRadius
	 *
	 * @return EditResultBuilder
	 */
	private function getNewEditResultBuilder(
		?RevisionRecord $originalRevisionRecord = null,
		array $changeTags = [],
		int $manualRevertSearchRadius = 15
	) {
		$store = $this->createMock( RevisionStore::class );
		$store->method( 'getRevisionById' )
			->willReturnCallback( function ( int $id ) {
				return $this->getExistingRevision( $id );
			} );
		$store->method( 'getNextRevision' )
			->willReturnCallback( function ( RevisionRecord $rev ) {
				return $this->getExistingRevision( $rev->getId() + 1 );
			} );

		$options = new ServiceOptions(
			EditResultBuilder::CONSTRUCTOR_OPTIONS,
			[ MainConfigNames::ManualRevertSearchRadius => $manualRevertSearchRadius ]
		);

		return new EditResultBuilder(
			$store,
			$changeTags,
			$options
		);
	}

	/**
	 * Meant to reproduce the values provided by ChangeTags::getSoftwareTags.
	 *
	 * @return string[]
	 */
	private function getSoftwareTags(): array {
		return [
			"mw-contentmodelchange",
			"mw-new-redirect",
			"mw-removed-redirect",
			"mw-changed-redirect-target",
			"mw-blank",
			"mw-replace",
			"mw-rollback",
			"mw-undo",
			"mw-manual-revert",
		];
	}
}
