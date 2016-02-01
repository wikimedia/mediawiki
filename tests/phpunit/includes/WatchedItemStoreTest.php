<?php

/**
 * @author Addshore
 *
 * @covers WatchedItemStore
 */
class WatchedItemStoreTest extends PHPUnit_Framework_TestCase {

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|IDatabase
	 */
	private function getMockDb() {
		return $this->getMock( 'IDatabase' );
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|LoadBalancer
	 */
	private function getMockLoadBalancer( $mockDb ) {
		$mock = $this->getMockBuilder( 'LoadBalancer' )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'getConnection' )
			->will( $this->returnValue( $mockDb ) );
		return $mock;
	}

	private function getFakeRow( $userId, $timestamp ) {
		$fakeRow = new stdClass();
		$fakeRow->wl_user = $userId;
		$fakeRow->wl_notificationtimestamp = $timestamp;
		return $fakeRow;
	}

	public function testDuplicateEntry_nothingToDuplicate() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'select' )
			->will( $this->returnValue( new FakeResultWrapper( [] ) ) );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] )
		);

		$store->duplicateEntry(
			Title::newFromText( 'Old_Title' ),
			Title::newFromText( 'New_Title' )
		);
	}

	public function testDuplicateEntry_somethingToDuplicate() {
		$fakeRows = [
			$this->getFakeRow( 1, '20151212010101' ),
			$this->getFakeRow( 2, null ),
		];

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->at( 0 ) )
			->method( 'select' )
			->will( $this->returnValue( new FakeResultWrapper( $fakeRows ) ) );
		$mockDb->expects( $this->at( 1 ) )
			->method( 'replace' )
			->with(
				'watchlist',
				[ [ 'wl_user', 'wl_namespace', 'wl_title' ] ],
				[
					[
						'wl_user' => 1,
						'wl_namespace' => 0,
						'wl_title' => 'New_Title',
						'wl_notificationtimestamp' => '20151212010101',
					],
					[
						'wl_user' => 2,
						'wl_namespace' => 0,
						'wl_title' => 'New_Title',
						'wl_notificationtimestamp' => null,
					],
				],
				$this->isType( 'string' )
			);

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] )
		);

		$store->duplicateEntry(
			Title::newFromText( 'Old_Title' ),
			Title::newFromText( 'New_Title' )
		);
	}

}
