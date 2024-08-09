<?php

use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \ResetPageRandom
 * @group Database
 * @author Dreamy Jazz
 */
class ResetPageRandomTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return ResetPageRandom::class;
	}

	public function testExecuteWhenFromAfterTo() {
		$this->maintenance->setOption( 'from', '20240605040302' );
		$this->maintenance->setOption( 'to', '20240405060708' );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/--from has to be smaller than --to/' );
	}

	public function testExecuteWhenNoPages() {
		$this->maintenance->setOption( 'to', '20240605040302' );
		$this->maintenance->setOption( 'from', '20240405060708' );
		$this->maintenance->execute();
		$this->expectOutputRegex(
			'/Resetting page_random.*20240405060708.*20240605040302[\s\S]*' .
			'page_random reset complete.*changed 0 rows/'
		);
	}

	private function getPageRandomValueForPage( Title $title ) {
		return $this->newSelectQueryBuilder()
			->select( 'page_random' )
			->from( 'page' )
			->where( [ 'page_id' => $title->getId() ] )
			->fetchField();
	}

	/** @dataProvider provideDryRunValues */
	public function testExecuteWhenPages( $dryRun ) {
		// Create two pages, one which should be reset and the other which should not.
		ConvertibleTimestamp::setFakeTime( '20230505050505' );
		$pageToBeUnmodified = $this->getExistingTestPage()->getTitle();
		$pageRandomThatShouldNotChange = $this->getPageRandomValueForPage( $pageToBeUnmodified );
		ConvertibleTimestamp::setFakeTime( '20240505050505' );
		$pageToBeModified = $this->getExistingTestPage()->getTitle();
		$pageRandomThatShouldChangeWhenNotDryRun = $this->getPageRandomValueForPage( $pageToBeModified );
		// Run the maintenance script and assert on the output
		$this->maintenance->setOption( 'to', '20240605040302' );
		$this->maintenance->setOption( 'from', '20240405060708' );
		$this->maintenance->setOption( 'dry', $dryRun );
		$this->maintenance->execute();
		$this->expectOutputRegex(
			'/Resetting page_random.*20240405060708.*20240605040302[\s\S]*page_random ' .
			'reset complete.*changed 1 rows/'
		);
		// Assert that page_random for the page that should not be modified was left as-is.
		$this->assertSame(
			$pageRandomThatShouldNotChange,
			$this->getPageRandomValueForPage( $pageToBeUnmodified ),
			'Pages created outside the given time range should not be modified.'
		);
		// Unless the run was a dry run, we cannot assert on the value of the
		if ( $dryRun ) {
			$this->assertSame(
				$pageRandomThatShouldChangeWhenNotDryRun,
				$this->getPageRandomValueForPage( $pageToBeModified ),
				'No modifications of page_random should occur on a dry run.'
			);
		}
	}

	public static function provideDryRunValues() {
		return [
			'dry-run is set' => [ true ],
			'dry-run is not set' => [ false ],
		];
	}
}
