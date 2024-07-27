<?php

namespace MediaWiki\Tests\Maintenance;

use AttachLatest;
use IDBAccessObject;
use MediaWiki\Title\Title;

/**
 * @covers \AttachLatest
 * @group Database
 * @author Dreamy Jazz
 */
class AttachLatestTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return AttachLatest::class;
	}

	/**
	 * @param int $count
	 * @return Title[] The test pages as values, with the keys as the expected values of page_latest after the
	 *   maintenance script run.
	 */
	private function getPagesWithPageLatestAsZero( int $count ): array {
		$returnArray = [];
		for ( $i = 0; $i < $count; $i++ ) {
			$existingTestPage = $this->getExistingTestPage();
			$returnArray[$existingTestPage->getRevisionRecord()->getId()] = $existingTestPage->getTitle();
		}
		// Set the page_latest as 0 for the test on all pages in $returnArray
		$pageIds = array_map( static function ( $page ) {
			return $page->getId();
		}, $returnArray );
		if ( count( $pageIds ) ) {
			$this->getDb()->newUpdateQueryBuilder()
				->update( 'page' )
				->set( [ 'page_latest' => 0 ] )
				->where( [ 'page_id' => $pageIds ] )
				->execute();
		}
		return $returnArray;
	}

	/** @dataProvider provideExecute */
	public function testExecute( $brokenCount ) {
		$pagesThatNeedFixing = $this->getPagesWithPageLatestAsZero( $brokenCount );
		// Run the maintenance script with 'fix' specified to actually fix the broken page rows.
		$this->maintenance->setOption( 'fix', 1 );
		$this->maintenance->execute();
		// Verify that the page_latest field has been fixed for all the pages that needed fixed.
		$expectedOutputRegex = '/';
		foreach ( $pagesThatNeedFixing as $expectedLatestRevId => $title ) {
			$expectedOutputRegex .= '.*' . $title->getPrefixedText() . '.*' . $expectedLatestRevId . "\n";
			$this->assertSame(
				$expectedLatestRevId,
				$title->getLatestRevID( IDBAccessObject::READ_LATEST ),
				'page_latest was not properly fixed by the maintenance script'
			);
		}
		$expectedOutputRegex .= ".*Done! Processed $brokenCount pages./";
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideExecute() {
		return [
			'No broken pages' => [ 0 ],
			'Five broken pages' => [ 5 ],
		];
	}
}
