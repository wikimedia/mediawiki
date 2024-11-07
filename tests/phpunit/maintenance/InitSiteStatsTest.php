<?php

namespace MediaWiki\Tests\Maintenance;

use InitSiteStats;
use MediaWiki\MainConfigNames;
use MediaWiki\SiteStats\SiteStats;
use MediaWiki\Title\Title;

/**
 * @covers \InitSiteStats
 * @group Database
 * @author Dreamy Jazz
 */
class InitSiteStatsTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return InitSiteStats::class;
	}

	/** @dataProvider provideExecute */
	public function testExecute( $options, $expectedRow, $expectedOutputString ) {
		$this->overrideConfigValue( MainConfigNames::ArticleCountMethod, 'any' );
		// Truncate the site_stats table so that we can check whether it gets populated by the maintenance script
		$this->truncateTable( 'site_stats' );
		// Run the maintenance script
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		$this->expectOutputString( $expectedOutputString );
		$this->newSelectQueryBuilder()
			->select( SiteStats::selectFields() )
			->from( 'site_stats' )
			->caller( __METHOD__ )
			->assertRowValue( array_values( $expectedRow ) );
	}

	public static function provideExecute() {
		return [
			'Dry-run' => [
				[],
				[
					'ss_total_edits' => 0, 'ss_good_articles' => 0, 'ss_total_pages' => 0,
					'ss_users' => 0, 'ss_active_users' => 0, 'ss_images' => 0,
				],
				"Refresh Site Statistics\n\nCounting total edits...3\nCounting number of articles...1\n" .
				"Counting total pages...2\nCounting number of users...3\nCounting number of images...2\n\n" .
				"To update the site statistics table, run the script with the --update option.\n\nDone.\n",
			],
			'Non dry-run' => [
				[ 'update' => 1 ],
				[
					'ss_total_edits' => 3, 'ss_good_articles' => 1, 'ss_total_pages' => 2,
					'ss_users' => 3, 'ss_active_users' => 0, 'ss_images' => 2,
				],
				"Refresh Site Statistics\n\nCounting total edits...3\nCounting number of articles...1\n" .
				"Counting total pages...2\nCounting number of users...3\nCounting number of images...2\n\n" .
				"Updating site statistics...done.\n\nDone.\n",
			],
			'Non dry-run with also calculating active users number' => [
				[ 'update' => 1, 'active' => 1 ],
				[
					'ss_total_edits' => 3, 'ss_good_articles' => 1, 'ss_total_pages' => 2,
					'ss_users' => 3, 'ss_active_users' => 2, 'ss_images' => 2,
				],
				"Refresh Site Statistics\n\nCounting total edits...3\nCounting number of articles...1\n" .
				"Counting total pages...2\nCounting number of users...3\nCounting number of images...2\n\n" .
				"Updating site statistics...done.\n\nCounting and updating active users...2\n\nDone.\n",
			],
		];
	}

	public function addDBDataOnce() {
		$firstTestUser = $this->getMutableTestUser()->getUser();
		$secondTestUser = $this->getMutableTestUser()->getUser();
		$thirdTestUser = $this->getMutableTestUser()->getUser();
		// Create a testing article with two edits
		$testPage = Title::newFromText( 'Testing1234' );
		$this->editPage( $testPage, 'testing', '', NS_MAIN, $firstTestUser );
		$this->editPage( $testPage, 'testingcontent1234', '', NS_MAIN, $secondTestUser );
		// Create a user page for the first test user
		$this->editPage( $firstTestUser->getUserPage(), 'testingabc', '', NS_MAIN, $firstTestUser );
		// Add some testing rows to the image table to simulate images existing.
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'image' )
			->rows( [
				[
					'img_name' => 'Random-13m.png',
					'img_size' => 54321,
					'img_width' => 1000,
					'img_height' => 1800,
					'img_metadata' => '',
					'img_bits' => 16,
					'img_media_type' => MEDIATYPE_BITMAP,
					'img_major_mime' => 'image',
					'img_minor_mime' => 'png',
					'img_description_id' => 0,
					'img_actor' => $firstTestUser->getActorId(),
					'img_timestamp' => $this->getDb()->timestamp( '20201105234242' ),
					'img_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80yu',
				],
				[
					'img_name' => 'Random-112m.png',
					'img_size' => 54321,
					'img_width' => 1000,
					'img_height' => 1800,
					'img_metadata' => '',
					'img_bits' => 16,
					'img_media_type' => MEDIATYPE_BITMAP,
					'img_major_mime' => 'image',
					'img_minor_mime' => 'png',
					'img_description_id' => 0,
					'img_actor' => $firstTestUser->getActorId(),
					'img_timestamp' => $this->getDb()->timestamp( '20201105235242' ),
					'img_sha1' => 'sy02psim0bgdh0jt4vdltuzoh7j80ru',
				],
			] )
			->execute();
	}
}
