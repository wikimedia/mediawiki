<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Maintenance\ApplyChangeTag;

/**
 * @covers \MediaWiki\Maintenance\ApplyChangeTag
 * @group Database
 */
class ApplyChangeTagTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return ApplyChangeTag::class;
	}

	public function testExecuteForUndefinedTag() {
		$this->maintenance->setOption( 'tag', 'undefined-tag' );
		$this->maintenance->setOption( 'revisions', '1' );
		$this->expectOutputRegex( "/Tag 'undefined-tag' is not defined/" );
		$this->expectCallToFatalError();
		$this->maintenance->execute();
	}

	public function testExecuteWithNoTargets() {
		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'test-tag' );
		$this->maintenance->setOption( 'tag', 'test-tag' );
		$this->expectOutputRegex( '/at least one of --revisions or --logs/' );
		$this->expectCallToFatalError();
		$this->maintenance->execute();
	}

	public function testExecuteAppliesTagToRevisionAndSkipsMissing() {
		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'test-tag' );
		$revId = $this->getExistingTestPage()->getLatest();
		$missingId = $revId + 1000;

		$this->maintenance->setOption( 'tag', 'test-tag' );
		$this->maintenance->setOption( 'revisions', "$revId,$missingId" );
		$this->expectOutputRegex(
			"/Skipping revision IDs that do not exist: $missingId.*Applied 'test-tag' to 1 target/s"
		);
		$this->maintenance->execute();

		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'change_tag' )
			->join( 'change_tag_def', null, 'ct_tag_id=ctd_id' )
			->where( [ 'ct_rev_id' => $revId, 'ctd_name' => 'test-tag' ] )
			->assertFieldValue( 1 );
	}

	public function testExecuteAppliesTagToLogEntry() {
		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'test-tag' );
		$logEntry = new ManualLogEntry( 'move', 'move' );
		$logEntry->setPerformer( $this->getTestUser()->getUser() );
		$logEntry->setTarget( $this->getExistingTestPage()->getTitle() );
		$logId = $logEntry->insert();

		$this->maintenance->setOption( 'tag', 'test-tag' );
		$this->maintenance->setOption( 'logs', (string)$logId );
		$this->maintenance->execute();

		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'change_tag' )
			->where( [ 'ct_log_id' => $logId ] )
			->assertFieldValue( 1 );
	}

	public function testExecuteAppliesSoftwareDefinedTag() {
		$this->setTemporaryHook( 'ListDefinedTags', static function ( array &$tags ) {
			$tags[] = 'test-software-tag';
		} );
		$revId = $this->getExistingTestPage()->getLatest();

		$this->maintenance->setOption( 'tag', 'test-software-tag' );
		$this->maintenance->setOption( 'revisions', (string)$revId );
		$this->maintenance->execute();

		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'change_tag' )
			->join( 'change_tag_def', null, 'ct_tag_id=ctd_id' )
			->where( [ 'ct_rev_id' => $revId, 'ctd_name' => 'test-software-tag' ] )
			->assertFieldValue( 1 );
	}
}
