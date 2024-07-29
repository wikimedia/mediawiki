<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use UpdateArticleCount;

/**
 * @covers \UpdateArticleCount
 * @group Database
 * @author Dreamy Jazz
 */
class UpdateArticleCountTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return UpdateArticleCount::class;
	}

	/**
	 * @param int $count
	 * @return Title[]
	 */
	private function getExistingTestPages( int $count ): array {
		$returnArray = [];
		for ( $i = 0; $i < $count; $i++ ) {
			$returnArray[] = $this->getExistingTestPage()->getTitle();
		}
		return $returnArray;
	}

	private function getExistingTestPagesWithLinks( int $count ) {
		$titles = $this->getExistingTestPages( $count );
		// Replace the content of the existing pages with a wikilink.
		foreach ( $titles as $title ) {
			$this->editPage( $title, '[[Test]]' );
		}
	}

	/** @dataProvider provideExecute */
	public function testExecute(
		$options, $existingPagesCount, $existingPagesWithLinksCount, $articleCountMethod,
		$expectedArticleCountInDb, $expectedOutputString
	) {
		$this->overrideConfigValue( MainConfigNames::ArticleCountMethod, $articleCountMethod );
		// Get some testing pages
		$this->getExistingTestPages( $existingPagesCount );
		$this->getExistingTestPagesWithLinks( $existingPagesWithLinksCount );
		// Call ::doUpdates to ensure that the site_stats table is properly updated before we set the value to 0.
		DeferredUpdates::doUpdates();
		// Set the site_stats ss_good_articles to 0 to test that it changes after the maintenance script runs.
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'site_stats' )
			->set( [ 'ss_good_articles' => 0 ] )
			->where( [ 'ss_row_id' => 1 ] )
			->execute();
		// Run the maintenance script
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		// Verify that the output is as expected and that the ss_good_articles column has the correct value.
		$this->expectOutputString( $expectedOutputString );
		$this->assertSelect(
			'site_stats', 'ss_good_articles', [ 'ss_row_id' => 1 ],
			[ [ $expectedArticleCountInDb ] ],
		);
	}

	public static function provideExecute() {
		return [
			'Dry-run, use-master specified, 2 pages without links, 1 with links, wgArticleCountMethod as all' => [
				[ 'use-master' => 1 ], 2, 1, 'all', 0,
				"Counting articles...found 3.\nTo update the site statistics table, run the script " .
				"with the --update option.\n"
			],
			'1 page without links, 4 pages with links, wgArticleCountMethod as link' => [
				[ 'update' => 1 ], 1, 4, 'link', 4,
				"Counting articles...found 4.\nUpdating site statistics table...done.\n"
			],
		];
	}
}
