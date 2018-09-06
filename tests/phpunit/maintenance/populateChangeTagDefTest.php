<?php

namespace MediaWiki\Tests\Maintenance;

use PopulateChangeTagDef;

/**
 * @group Database
 * @covers PopulateChangeTagDef
 */
class PopulateChangeTagDefTest extends MaintenanceBaseTestCase {

	public function getMaintenanceClass() {
		return PopulateChangeTagDef::class;
	}

	public function setUp() {
		parent::setUp();
		$this->tablesUsed = [ 'change_tag', 'change_tag_def', 'updatelog' ];

		$this->cleanChangeTagTables();
		$this->insertChangeTagData();
	}

	private function cleanChangeTagTables() {
		wfGetDB( DB_MASTER )->delete( 'change_tag', '*' );
		wfGetDB( DB_MASTER )->delete( 'change_tag_def', '*' );
		wfGetDB( DB_MASTER )->delete( 'updatelog', '*' );
	}

	private function insertChangeTagData() {
		$changeTags = [];

		$changeTags[] = [
			'ct_rc_id' => 1234,
			'ct_tag' => 'One Tag',
		];

		$changeTags[] = [
			'ct_rc_id' => 1235,
			'ct_tag' => 'Two Tags',
		];

		$changeTags[] = [
			'ct_log_id' => 1236,
			'ct_tag' => 'Two Tags',
		];

		$changeTags[] = [
			'ct_rev_id' => 1237,
			'ct_tag' => 'Three Tags',
		];

		$changeTags[] = [
			'ct_rc_id' => 1238,
			'ct_tag' => 'Three Tags',
		];

		$changeTags[] = [
			'ct_log_id' => 1239,
			'ct_tag' => 'Three Tags',
		];

		wfGetDB( DB_MASTER )->insert( 'change_tag', $changeTags );
	}

	public function testRun() {
		$this->setMwGlobals( 'wgChangeTagsSchemaMigrationStage', MIGRATION_WRITE_BOTH );
		$this->maintenance->loadWithArgv( [ '--sleep', '0' ] );

		$this->maintenance->execute();

		$changeTagDefRows = [
			(object)[
				'ctd_name' => 'One Tag',
				'ctd_count' => 1,
			],
			(object)[
				'ctd_name' => 'Two Tags',
				'ctd_count' => 2,
			],
			(object)[
				'ctd_name' => 'Three Tags',
				'ctd_count' => 3,
			],
		];

		$actualChangeTagDefs = wfGetDB( DB_REPLICA )->select(
			[ 'change_tag_def' ],
			[ 'ctd_name', 'ctd_count' ],
			[],
			__METHOD__,
			[ 'ORDER BY' => 'ctd_count' ]
		);

		$this->assertEquals( $changeTagDefRows, iterator_to_array( $actualChangeTagDefs, false ) );

		// Check if change_tag is also backpopulated
		$actualChangeTags = wfGetDB( DB_REPLICA )->select(
			[ 'change_tag', 'change_tag_def' ],
			[ 'ct_tag', 'ct_tag_id', 'ctd_count' ],
			[],
			__METHOD__,
			[],
			[ 'change_tag_def' => [ 'LEFT JOIN', 'ct_tag_id=ctd_id' ] ]
		);
		$mapping = [
			'One Tag' => 1,
			'Two Tags' => 2,
			'Three Tags' => 3
		];
		foreach ( $actualChangeTags as $row ) {
			$this->assertNotNull( $row->ct_tag_id );
			$this->assertEquals( $row->ctd_count, $mapping[$row->ct_tag] );
		}
	}

	public function testRunUpdateHitCountMigrationNew() {
		$this->setMwGlobals( 'wgChangeTagsSchemaMigrationStage', MIGRATION_NEW );
		$changeTagDefBadRows = [
			[
				'ctd_name' => 'One Tag',
				'ctd_user_defined' => 0,
				'ctd_count' => 50,
			],
			[
				'ctd_name' => 'Two Tags',
				'ctd_user_defined' => 0,
				'ctd_count' => 4,
			],
			[
				'ctd_name' => 'Three Tags',
				'ctd_user_defined' => 0,
				'ctd_count' => 3,
			],
		];
		wfGetDB( DB_MASTER )->insert(
			'change_tag_def',
			$changeTagDefBadRows
		);

		$mapping = [
			'One Tag' => 1,
			'Two Tags' => 2,
			'Three Tags' => 3
		];
		foreach ( $mapping as $tagName => $tagId ) {
			wfGetDB( DB_MASTER )->update(
				'change_tag',
				[ 'ct_tag_id' => $tagId ],
				[ 'ct_tag' => $tagName ]
			);
		}

		$this->maintenance->loadWithArgv( [ '--sleep', '0' ] );

		$this->maintenance->execute();

		$changeTagDefRows = [
			(object)[
				'ctd_name' => 'One Tag',
				'ctd_count' => 1,
			],
			(object)[
				'ctd_name' => 'Two Tags',
				'ctd_count' => 2,
			],
			(object)[
				'ctd_name' => 'Three Tags',
				'ctd_count' => 3,
			],
		];

		$actualChangeTagDefs = wfGetDB( DB_REPLICA )->select(
			[ 'change_tag_def' ],
			[ 'ctd_name', 'ctd_count' ],
			[],
			__METHOD__,
			[ 'ORDER BY' => 'ctd_count' ]
		);

		$this->assertEquals( $changeTagDefRows, iterator_to_array( $actualChangeTagDefs, false ) );
	}

	public function testRunUpdateHitCountMigrationWriteBoth() {
		$this->setMwGlobals( 'wgChangeTagsSchemaMigrationStage', MIGRATION_WRITE_BOTH );
		$changeTagDefBadRows = [
			[
				'ctd_name' => 'One Tag',
				'ctd_user_defined' => 0,
				'ctd_count' => 50,
			],
			[
				'ctd_name' => 'Two Tags',
				'ctd_user_defined' => 0,
				'ctd_count' => 4,
			],
			[
				'ctd_name' => 'Three Tags',
				'ctd_user_defined' => 0,
				'ctd_count' => 3,
			],
		];
		wfGetDB( DB_MASTER )->insert(
			'change_tag_def',
			$changeTagDefBadRows
		);

		$this->maintenance->loadWithArgv( [ '--sleep', '0' ] );

		$this->maintenance->execute();

		$changeTagDefRows = [
			(object)[
				'ctd_name' => 'One Tag',
				'ctd_count' => 1,
			],
			(object)[
				'ctd_name' => 'Two Tags',
				'ctd_count' => 2,
			],
			(object)[
				'ctd_name' => 'Three Tags',
				'ctd_count' => 3,
			],
		];

		$actualChangeTagDefs = wfGetDB( DB_REPLICA )->select(
			[ 'change_tag_def' ],
			[ 'ctd_name', 'ctd_count' ],
			[],
			__METHOD__,
			[ 'ORDER BY' => 'ctd_count' ]
		);

		$this->assertEquals( $changeTagDefRows, iterator_to_array( $actualChangeTagDefs, false ) );
	}

	public function testDryRunMigrationNew() {
		$this->setMwGlobals( 'wgChangeTagsSchemaMigrationStage', MIGRATION_NEW );
		$this->maintenance->loadWithArgv( [ '--dry-run', '--sleep', '0' ] );

		$this->maintenance->execute();

		$actualChangeTagDefs = wfGetDB( DB_REPLICA )->select(
			[ 'change_tag_def' ],
			[ 'ctd_id', 'ctd_name' ]
		);

		$this->assertEquals( [], iterator_to_array( $actualChangeTagDefs, false ) );

		$actualChangeTags = wfGetDB( DB_REPLICA )->select(
			[ 'change_tag' ],
			[ 'ct_tag_id', 'ct_tag' ]
		);

		foreach ( $actualChangeTags as $row ) {
			$this->assertNull( $row->ct_tag_id );
			$this->assertNotNull( $row->ct_tag );
		}
	}

	public function testDryRunMigrationWriteBoth() {
		$this->setMwGlobals( 'wgChangeTagsSchemaMigrationStage', MIGRATION_WRITE_BOTH );
		$this->maintenance->loadWithArgv( [ '--dry-run', '--sleep', '0' ] );

		$this->maintenance->execute();

		$actualChangeTagDefs = wfGetDB( DB_REPLICA )->select(
			[ 'change_tag_def' ],
			[ 'ctd_id', 'ctd_name' ]
		);

		$this->assertEquals( [], iterator_to_array( $actualChangeTagDefs, false ) );

		$actualChangeTags = wfGetDB( DB_REPLICA )->select(
			[ 'change_tag' ],
			[ 'ct_tag_id', 'ct_tag' ]
		);

		foreach ( $actualChangeTags as $row ) {
			$this->assertNull( $row->ct_tag_id );
			$this->assertNotNull( $row->ct_tag );
		}
	}

}
