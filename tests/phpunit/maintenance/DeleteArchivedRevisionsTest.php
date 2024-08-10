<?php

namespace MediaWiki\Tests\Maintenance;

use DeleteArchivedRevisions;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @covers \DeleteArchivedRevisions
 * @group Database
 * @author Dreamy Jazz
 */
class DeleteArchivedRevisionsTest extends MaintenanceBaseTestCase {
	use MockAuthorityTrait;

	protected function getMaintenanceClass() {
		return DeleteArchivedRevisions::class;
	}

	/** @dataProvider provideExecute */
	public function testExecute( $options, $expectedRowCountAfterExecution, $expectedOutputRegex ) {
		// Create a page and then delete it
		$testPage = $this->getExistingTestPage();
		$deleteStatus = $this->getServiceContainer()->getDeletePageFactory()
			->newDeletePage( $testPage, $this->mockRegisteredUltimateAuthority() )
			->deleteIfAllowed( 'test' );
		$this->assertStatusGood( $deleteStatus );
		// Check that this has caused a revision to be added to the archive table
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'archive' )
			->assertFieldValue( 1 );
		// Call ::execute
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		// Verify that the archive table is now empty.
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'archive' )
			->assertFieldValue( $expectedRowCountAfterExecution );
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideExecute() {
		return [
			'Dry-run' => [ [], 1, '/Found 1 revisions to delete/' ],
			'Actual deletion' => [ [ 'delete' => 1 ], 0, '/Deleting archived revisions.*1 revisions deleted/' ],
		];
	}
}
