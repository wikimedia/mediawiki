<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Maintenance\Maintenance;
use PHPUnit\Framework\MockObject\MockObject;
use PurgeOldText;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers PurgeOldText
 * @author Dreamy Jazz
 */
class PurgeOldTextTest extends MaintenanceBaseTestCase {

	/** @var Maintenance|MockObject */
	protected $maintenance;

	public function getMaintenanceClass() {
		return PurgeOldText::class;
	}

	protected function createMaintenance() {
		// Mock ::purgeRedundantText as this should be separately tested
		$obj = $this->getMockBuilder( $this->getMaintenanceClass() )
			->onlyMethods( [ 'purgeRedundantText' ] )
			->getMock();
		return TestingAccessWrapper::newFromObject( $obj );
	}

	/** @dataProvider providePurgeOptionValues */
	public function testExecute( $purgeOptionProvided ) {
		$this->maintenance->expects( $this->once() )
			->method( 'purgeRedundantText' )
			->with( $purgeOptionProvided );
		if ( $purgeOptionProvided ) {
			$this->maintenance->setOption( 'purge', 1 );
		}
		$this->maintenance->execute();
	}

	public static function providePurgeOptionValues() {
		return [
			'--purge not provided' => [ false ],
			'--purge provided' => [ true ],
		];
	}
}
