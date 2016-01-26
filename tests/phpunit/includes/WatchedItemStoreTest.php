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
	 * @return PHPUnit_Framework_MockObject_MockObject|Config
	 */
	private function getMockConfig() {
		return $this->getMock( 'Config' );
	}

	private function getFakeRow( array $rowValues ) {
		$fakeRow = new stdClass();
		foreach ( $rowValues as $valueName => $value ) {
			$fakeRow->$valueName = $value;
		}
		return $fakeRow;
	}

	public function testCountWatchers() {
		$titleValue = new TitleValue( 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'selectField' )
			->with(
				'watchlist',
				'COUNT(*)',
				array(
					'wl_namespace' => $titleValue->getNamespace(),
					'wl_title' => $titleValue->getDBkey(),
				),
				$this->isType( 'string' )
			)
			->will( $this->returnValue( 7 ) );

		$store = new WatchedItemStore( $this->getMockDb(), $mockDb, $this->getMockConfig() );

		$this->assertEquals( 7, $store->countWatchers( $titleValue ) );
	}

	public function testCountWatchersMultiple() {
		$titleValues = array(
			new TitleValue( 0, 'SomeDbKey' ),
			new TitleValue( 0, 'OtherDbKey' ),
			new TitleValue( 1, 'AnotherDbKey' ),
		);

		$mockDb = $this->getMockDb();
		$mockConfig = $this->getMockConfig();

		$dbResult = array(
			$this->getFakeRow( array( 'wl_title' => 'SomeDbKey', 'wl_namespace' => 0, 'count' => 100 ) ),
			$this->getFakeRow( array( 'wl_title' => 'OtherDbKey', 'wl_namespace' => 0, 'count' => 300 ) ),
			$this->getFakeRow( array( 'wl_title' => 'AnotherDbKey', 'wl_namespace' => 1, 'count' => 500 ) ),
		);
		$mockDb->expects( $this->once() )
			->method( 'makeWhereFrom2d' )
			->with(
				array( array( 'SomeDbKey' => 1, 'OtherDbKey' => 1 ), array( 'AnotherDbKey' => 1 ) ),
				$this->isType( 'string' ),
				$this->isType( 'string' )
				)
			->will( $this->returnValue( 'makeWhereFrom2d return value' ) );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				'watchlist',
				array( 'wl_title', 'wl_namespace', 'count' => 'COUNT(*)' ),
				array( 'makeWhereFrom2d return value' ),
				$this->isType( 'string' ),
				array(
					'GROUP BY' => array( 'wl_namespace', 'wl_title' ),
					'HAVING' => 'COUNT(*) >= 60',
				)
			)
			->will(
				$this->returnValue( $dbResult )
			);
		$mockConfig->expects( $this->exactly( 1 ) )
			->method( 'get' )
			->with( 'UnwatchedPageThreshold' )
			->will( $this->returnValue( 60 ) );

		$store = new WatchedItemStore( $this->getMockDb(), $mockDb, $mockConfig );

		$expected = array(
			0 => array( 'SomeDbKey' => 100, 'OtherDbKey' => 300 ),
			1 => array( 'AnotherDbKey' => 500 ),
		);
		$this->assertEquals( $expected, $store->countWatchersMultiple( $titleValues ) );
	}

	public function testDuplicateEntries_nothingToDuplicate() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'select' )
			->will( $this->returnValue( new FakeResultWrapper( array() ) ) );

		$store = new WatchedItemStore( $mockDb, $this->getMockDb(), $this->getMockConfig() );

		$store->duplicateEntries(
			new TitleValue( 0, 'Old_Title' ),
			new TitleValue( 0, 'New_Title' )
		);
	}

	public function testDuplicateEntries_somethingToDuplicate() {
		$fakeRows = array(
			$this->getFakeRow( array( 'wl_user' => 1, 'wl_notificationtimestamp' => '20151212010101' ) ),
			$this->getFakeRow( array( 'wl_user' => 2, 'wl_notificationtimestamp' => null ) ),
		);

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->at( 0 ) )
			->method( 'select' )
			->will( $this->returnValue( new FakeResultWrapper( $fakeRows ) ) );
		$mockDb->expects( $this->at( 1 ) )
			->method( 'replace' )
			->with(
				'watchlist',
				array( array( 'wl_user', 'wl_namespace', 'wl_title' ) ),
				array(
					array(
						'wl_user' => 1,
						'wl_namespace' => 0,
						'wl_title' => 'New_Title',
						'wl_notificationtimestamp' => '20151212010101',
					),
					array(
						'wl_user' => 2,
						'wl_namespace' => 0,
						'wl_title' => 'New_Title',
						'wl_notificationtimestamp' => null,
					),
				),
				$this->isType( 'string' )
			);
		$mockDb->expects( $this->at( 2 ) )
			->method( 'select' )
			->will( $this->returnValue( new FakeResultWrapper( $fakeRows ) ) );
		$mockDb->expects( $this->at( 3 ) )
			->method( 'replace' )
			->with(
				'watchlist',
				array( array( 'wl_user', 'wl_namespace', 'wl_title' ) ),
				array(
					array(
						'wl_user' => 1,
						'wl_namespace' => 1,
						'wl_title' => 'New_Title',
						'wl_notificationtimestamp' => '20151212010101',
					),
					array(
						'wl_user' => 2,
						'wl_namespace' => 1,
						'wl_title' => 'New_Title',
						'wl_notificationtimestamp' => null,
					),
				),
				$this->isType( 'string' )
			);

		$store = new WatchedItemStore( $mockDb, $this->getMockDb(), $this->getMockConfig() );

		$store->duplicateEntries(
			new TitleValue( 0, 'Old_Title' ),
			new TitleValue( 0, 'New_Title' )
		);
	}

}
