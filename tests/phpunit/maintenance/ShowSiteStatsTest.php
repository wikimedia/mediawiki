<?php

namespace MediaWiki\Tests\Maintenance;

use ShowSiteStats;

/**
 * @covers \ShowSiteStats
 * @group Database
 * @author Dreamy Jazz
 */
class ShowSiteStatsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return ShowSiteStats::class;
	}

	public function testExecute() {
		$this->maintenance->execute();
		$this->expectOutputString(
			"Total edits       : 123\n" .
			"Number of articles:  12\n" .
			"Total pages       :  20\n" .
			"Number of users   :  14\n" .
			"Active users      :   4\n" .
			"Number of images  :   3\n"
		);
	}

	public function addDBData() {
		// Add test data to the site_stats table.
		$this->getDb()->newReplaceQueryBuilder()
			->table( 'site_stats' )
			->row( [
				'ss_row_id' => 1,
				'ss_total_edits' => 123,
				'ss_good_articles' => 12,
				'ss_total_pages' => 20,
				'ss_users' => 14,
				'ss_active_users' => 4,
				'ss_images' => 3,
			] )
			->uniqueIndexFields( [ 'ss_row_id' ] )
			->caller( __METHOD__ )
			->execute();
	}
}
