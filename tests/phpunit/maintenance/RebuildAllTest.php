<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Maintenance\Maintenance;
use RebuildAll;
use RebuildRecentchanges;
use RebuildTextIndex;
use RefreshLinks;

/**
 * @covers \RebuildAll
 * @group Database
 * @author Dreamy Jazz
 */
class RebuildAllTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return RebuildAll::class;
	}

	protected function createMaintenance() {
		return $this->getMockBuilder( RebuildAll::class )
			->onlyMethods( [ 'createChild' ] )
			->getMock();
	}

	public function testExecute() {
		$actualCreateChildCalls = [];
		$maintenance = $this->createMaintenance();
		$maintenance->method( 'createChild' )
			->willReturnCallback(
				function (
					string $maintClass, ?string $classFile
				) use ( &$actualCreateChildCalls ) {
					$actualCreateChildCalls[] = [ $maintClass, $classFile ];

					// Return a mocked Maintenance class to avoid testing the child scripts.
					// These should be tested via separate tests.
					$mockMaintenance = $this->createMock( Maintenance::class );
					$mockMaintenance->expects( $this->once() )
						->method( 'execute' );
					return $mockMaintenance;
				}
			);
		$maintenance->execute();

		// Verify that createChild was called for the maintenance scripts that are run by rebuildall.php
		$expectedCreateChildCalls = [];
		if ( $this->getDb()->getType() !== 'postgres' ) {
			$expectedCreateChildCalls[] = [ RebuildTextIndex::class, 'rebuildtextindex.php' ];
		}
		$expectedCreateChildCalls[] = [ RebuildRecentchanges::class, 'rebuildrecentchanges.php' ];
		$expectedCreateChildCalls[] = [ RefreshLinks::class, 'refreshLinks.php' ];

		$this->assertSame( $expectedCreateChildCalls, $actualCreateChildCalls );

		// Expect that the script outputs that it's running the above scripts
		$actualOutput = $this->getActualOutputForAssertion();
		if ( $this->getDb()->getType() === 'postgres' ) {
			$this->assertStringNotContainsString( 'Rebuilding fulltext search index', $actualOutput );
		} else {
			$this->assertStringContainsString( 'Rebuilding fulltext search index', $actualOutput );
		}
		$this->assertStringContainsString( 'Rebuilding recentchanges table', $actualOutput );
		$this->assertStringContainsString( 'Rebuilding links tables', $actualOutput );
	}
}
