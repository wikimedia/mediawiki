<?php

namespace MediaWiki\Tests\Maintenance;

use ClearInterwikiCache;
use MediaWiki\Interwiki\InterwikiLookup;

/**
 * @covers \ClearInterwikiCache
 * @group Database
 * @author Dreamy Jazz
 */
class ClearInterwikiCacheTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return ClearInterwikiCache::class;
	}

	public function testExecute() {
		// Insert some testing interwiki table rows
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'interwiki' )
			->rows( [
				[
					'iw_prefix' => 'en',
					'iw_url' => 'test$1',
					'iw_api' => 'testapi$1',
					'iw_wikiid' => 'enwiki',
					'iw_local' => 0,
					'iw_trans' => 0,
				],
				[
					'iw_prefix' => 'de',
					'iw_url' => 'de.test$1',
					'iw_api' => 'de.testapi$1',
					'iw_wikiid' => 'dewiki',
					'iw_local' => 0,
					'iw_trans' => 0,
				],
			] )
			->execute();
		// Mock that the InterwikiLookup::invalidateCache method is called.
		$this->setService( 'InterwikiLookup', function () {
			$mockInterwikiLookup = $this->createMock( InterwikiLookup::class );
			$mockInterwikiLookup->expects( $this->exactly( 2 ) )
				->method( 'invalidateCache' )
				->willReturnCallback( function ( $prefix ) {
					$this->assertContains( $prefix, [ 'en', 'de' ] );
				} );
			return $mockInterwikiLookup;
		} );
		$this->maintenance->execute();
	}
}
