<?php

namespace MediaWiki\Tests\Maintenance;

use CheckBadRedirects;
use MediaWiki\Title\Title;

/**
 * @covers \CheckBadRedirects
 * @group Database
 * @author Dreamy Jazz
 */
class CheckBadRedirectsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CheckBadRedirects::class;
	}

	/**
	 * @param int $count
	 * @return Title[] The titles which are bad redirects
	 */
	private function getPagesIncorrectlyMarkedAsRedirects( int $count ): array {
		$returnArray = [];
		for ( $i = 0; $i < $count; $i++ ) {
			$existingTestPage = $this->getExistingTestPage();
			$returnArray[] = $existingTestPage->getTitle();
		}
		// Set the page_is_redirect as 1 for the test on all pages in $returnArray
		$pageIds = array_map( static function ( $page ) {
			return $page->getId();
		}, $returnArray );
		if ( count( $pageIds ) ) {
			$this->getDb()->newUpdateQueryBuilder()
				->update( 'page' )
				->set( [ 'page_is_redirect' => 1 ] )
				->where( [ 'page_id' => $pageIds ] )
				->execute();
		}
		return $returnArray;
	}

	/**
	 * @param int $count
	 * @return Title[] The titles which are good redirects
	 */
	private function getRedirectPages( int $count ): array {
		$returnArray = [];
		for ( $i = 0; $i < $count; $i++ ) {
			$existingTestPage = $this->getExistingTestPage();
			$this->editPage(
				$existingTestPage,
				$this->getServiceContainer()->getContentHandlerFactory()
					->getContentHandler( CONTENT_MODEL_WIKITEXT )
					->makeRedirectContent( $this->getExistingTestPage()->getTitle() )
			);
			$returnArray[] = $existingTestPage->getTitle();
		}
		return $returnArray;
	}

	/** @dataProvider provideExecute */
	public function testExecute( $brokenCount, $validCount, $expectedRedirectsCount ) {
		$redirectsAreValid = $this->getRedirectPages( $validCount );
		$pagesThatNeedFixing = $this->getPagesIncorrectlyMarkedAsRedirects( $brokenCount );
		// Run the maintenance script
		$this->maintenance->execute();
		// Verify that the count of redirects is expected.
		$expectedOutputRegex = "/[\S\s]*Found $expectedRedirectsCount redirects[\S\s]*";
		// Verify that the pages with an invalid page_is_redirect field are listed.
		foreach ( $pagesThatNeedFixing as $title ) {
			$expectedOutputRegex .= $title->getPrefixedText() . "\n";
		}
		$expectedOutputRegex .= "\nDone.\n/";
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideExecute() {
		return [
			'No broken pages' => [ 0, 3, 3 ],
			'Five broken pages' => [ 5, 2, 7 ],
		];
	}
}
