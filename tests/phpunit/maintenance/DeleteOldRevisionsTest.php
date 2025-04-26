<?php

namespace MediaWiki\Tests\Maintenance;

use DeleteOldRevisions;
use MediaWiki\Page\WikiPage;

/**
 * @covers \DeleteOldRevisions
 * @group Database
 * @author Dreamy Jazz
 */
class DeleteOldRevisionsTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return DeleteOldRevisions::class;
	}

	private function expectTheExpectedOutputRegex(
		bool $shouldDelete, int $specifiedPageId, int $expectedOldRevisionsCount
	) {
		$expectedOutputRegex = '/Delete old revisions';
		// Only expect the page IDs to be outputted if we specified some.
		if ( $specifiedPageId ) {
			$expectedOutputRegex .= '[\s\S]*Limiting to page IDs.*' . $specifiedPageId;
		} else {
			$expectedOutputRegex .= '[\s\S]*(?!Limiting to page IDs)';
		}
		$expectedOutputRegex .= '[\s\S]*Searching for active revisions.*done[\s\S]*' .
			'Searching for inactive revisions.*done[\s\S]*' .
			"$expectedOldRevisionsCount old revisions found";
		if ( $shouldDelete ) {
			$expectedOutputRegex .= '[\s\S]*Deleting/';
		} else {
			$expectedOutputRegex .= '[\s\S]*(?!Deleting)/';
		}
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	/** @dataProvider provideExecuteForNoPages */
	public function testExecuteForNoPages( $deleteOptionProvided ) {
		if ( $deleteOptionProvided ) {
			$this->maintenance->setOption( 'delete', 1 );
		}
		$this->maintenance->execute();
		$this->expectTheExpectedOutputRegex( false, 0, 0 );
	}

	public static function provideExecuteForNoPages() {
		return [
			'Delete option provided' => [ true ],
			'Delete option not provided' => [ false ],
		];
	}

	private function verifyRevisionCountForPage( WikiPage $page, int $expectedCount ) {
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'revision' )
			->where( [ 'rev_page' => $page->getId() ] )
			->assertFieldValue( $expectedCount );
	}

	/** @dataProvider provideExecute */
	public function testExecute( $shouldDelete, $shouldSpecifyPageId, $expectedOldRevisionsCount ) {
		$firstExistingTestPage = $this->getExistingTestPage();
		$secondExistingTestPage = $this->getExistingTestPage();
		$thirdExistingTestPage = $this->getExistingTestPage();
		// Make an edit to both the first and second existing test pages, so that they have old revisions
		$this->editPage( $firstExistingTestPage, 'abcdef' );
		$this->editPage( $secondExistingTestPage, 'abcef' );
		// Check that the revision count for each page is correct for the test.
		$this->verifyRevisionCountForPage( $thirdExistingTestPage, 1 );
		$this->verifyRevisionCountForPage( $secondExistingTestPage, 2 );
		$this->verifyRevisionCountForPage( $firstExistingTestPage, 2 );
		// Set the options for the maintenance script
		if ( $shouldDelete ) {
			$this->maintenance->setOption( 'delete', 1 );
		}
		if ( $shouldSpecifyPageId ) {
			$this->maintenance->setArg( 'page_id', $firstExistingTestPage->getId() );
		}
		// Run the maintenance script and then verify the number of revisions for each test page is as expected
		$this->maintenance->execute();
		$this->verifyRevisionCountForPage( $thirdExistingTestPage, 1 );
		$this->verifyRevisionCountForPage(
			$secondExistingTestPage,
			// Should not have been touched, unless --delete was specified and --page_id was not specified
			$shouldDelete && !$shouldSpecifyPageId ? 1 : 2
		);
		$this->verifyRevisionCountForPage(
			$firstExistingTestPage,
			// Should not be touched, unless --delete is specified.
			$shouldDelete ? 1 : 2
		);
		$this->expectTheExpectedOutputRegex(
			$shouldDelete,
			$shouldSpecifyPageId ? $firstExistingTestPage->getId() : 0,
			$expectedOldRevisionsCount
		);
	}

	public static function provideExecute() {
		return [
			'No options provided' => [ false, false, 2 ],
			'Not deleting, but specified page ID' => [ false, true, 1 ],
			'Deleting without specifying page IDs' => [ true, false, 2 ],
			'Deleting while specifying page ID' => [ true, true, 1 ],
		];
	}
}
