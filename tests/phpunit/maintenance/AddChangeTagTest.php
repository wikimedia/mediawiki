<?php

namespace MediaWiki\Tests\Maintenance;

use AddChangeTag;

/**
 * @covers \AddChangeTag
 * @group Database
 * @author Dreamy Jazz
 */
class AddChangeTagTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return AddChangeTag::class;
	}

	public function testExecuteForEmptyTagOption() {
		$this->expectCallToFatalError();
		$expectedErrorMessage = wfMessage( 'tags-create-no-name' )->text();
		$this->expectOutputRegex( "/$expectedErrorMessage/" );
		$this->maintenance->setOption( 'tag', '' );
		$this->maintenance->setOption( 'reason', 'test' );
		$this->maintenance->execute();
	}

	public function testExecuteForSuccessfulCreation() {
		$this->maintenance->setOption( 'tag', 'new-test-tag' );
		$this->maintenance->setOption( 'reason', 'testing' );
		$this->expectOutputRegex( '/new-test-tag was created/' );
		$this->maintenance->execute();
		// Validate that the tag actually exists in the DB.
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'change_tag_def' )
			->where( [ 'ctd_name' => 'new-test-tag' ] )
			->assertFieldValue( 1 );
	}
}
