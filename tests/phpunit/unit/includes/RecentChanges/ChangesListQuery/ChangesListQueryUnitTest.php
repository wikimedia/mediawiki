<?php

namespace MediaWiki\Tests\Unit\RecentChanges\ChangesListQuery;

use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery;
use MediaWiki\Tests\Unit\MockServiceDependenciesTrait;

/**
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery
 */
class ChangesListQueryUnitTest extends \MediaWikiUnitTestCase {
	use MockServiceDependenciesTrait;

	public static function provideSortAndTruncate() {
		return [
			'empty' => [
				10,
				[],
				[]
			],
			'ordering with duplicate timestamps' => [
				10,
				[
					(object)[ 'rc_timestamp' => '20250101000000', 'rc_id' => 3 ],
					(object)[ 'rc_timestamp' => '20250101000000', 'rc_id' => 4 ],
					(object)[ 'rc_timestamp' => '20250102000000', 'rc_id' => 1 ],
					(object)[ 'rc_timestamp' => '20250102000000', 'rc_id' => 2 ],
				],
				[ 2, 1, 4, 3 ],
			],
			'duplicates and truncation' => [
				2,
				[
					(object)[ 'rc_timestamp' => '20250101000000', 'rc_id' => 2 ],
					(object)[ 'rc_timestamp' => '20250101000000', 'rc_id' => 3 ],
					(object)[ 'rc_timestamp' => '20250102000000', 'rc_id' => 1 ],
					(object)[ 'rc_timestamp' => '20250102000000', 'rc_id' => 1 ],
				],
				[ 1, 3 ],
			]
		];
	}

	/**
	 * @dataProvider provideSortAndTruncate
	 * @param int|null $limit
	 * @param \stdClass[] $rows
	 * @param int[] $expected
	 */
	public function testSortAndTruncate( $limit, $rows, $expected ) {
		$config = $this->createMock( \MediaWiki\Config\ServiceOptions::class );
		$config->method( 'get' )->willReturnMap( [
			[ MainConfigNames::WatchlistExpiry, true ],
			[ MainConfigNames::MiserMode, false ],
			[ MainConfigNames::RCMaxAge, 7776000 ],
			[ MainConfigNames::EnableChangesListQueryPartitioning, false ],
			[ MainConfigNames::VirtualDomainsMapping, [] ],
			[ MainConfigNames::LearnerMemberSince, 0 ],
			[ MainConfigNames::ExperiencedUserMemberSince, 0 ],
			[ MainConfigNames::LearnerEdits, 0 ],
			[ MainConfigNames::ExperiencedUserEdits, 0 ],
		] );

		$query = $this->newServiceInstance( ChangesListQuery::class, [
			'config' => $config
		] );
		$finalRows = [];
		$query->sortAndTruncate( $rows, $limit, $finalRows );
		$result = [];
		foreach ( $finalRows as $row ) {
			$result[] = $row->rc_id;
		}
		$this->assertSame( $expected, $result );
	}
}
