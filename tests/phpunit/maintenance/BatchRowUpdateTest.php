<?php

require_once __DIR__ . "/../../../maintenance/BatchRowUpdate.php";

/**
 * Tests for BatchRowUpdate and its components
 */
class BatchRowUpdateTest extends MediaWikiTestCase {

	public function testWriterBasicFunctionality() {
		$db = $this->mockDb();
		$writer = new EchoBatchRowWriter( $db, /*clusterName*/false, 'echo_event', 'event_id' );

		$updates = array(
			array( 'something' => 'changed' ),
			array( 'otherthing' => 'changed' ),
			array( 'and' => 'something', 'else' => 'changed' ),
		);

		$db->expects( $this->exactly( count( $updates ) ) )
			->method( 'update' );

		$writer->write( $updates );
	}

	public function testReaderBasicIterate() {
		$db = $this->mockDb();
		$batchSize = 2;
		$reader = new EchoBatchRowIterator( $db, 'some_table', 'id_field', $batchSize );

		$response = $this->genSelectResult( $batchSize, /*numRows*/ 5 );
		$retval = array();
		// need iterator objects as thats what DatabaseType::select returns
		foreach ( $response as $rows ) {
			$retval[] = new ArrayIterator( $rows );
		}
		$db->expects( $this->exactly( count( $response ) ) )
			->method( 'select' )
			->will( $this->onConsecutiveCalls( $retval[0], $retval[1], $retval[2], $retval[3] ) );

		$pos = 0;
		foreach ( $reader as $rows ) {
			$this->assertEquals( $response[$pos], $rows, "Testing row in position $pos" );
			$pos++;
		}
		// -1 is because the final array() marks the end and isnt included
		$this->assertEquals( count( $retval ) - 1, $pos );
	}

	protected function genSelectResult( $batchSize, $numRows ) {
		$res = array();
		for ( $i = 0; $i < $numRows; $i += $batchSize ) {
			$rows = array();
			for ( $j = 0; $j < $batchSize && $i + $j < $numRows; $j++ ) {
				$rows [] = $this->genRow();
			}
			$res[] = $rows;
		}
		$res[] = array(); // termination condition requires empty result for last row
		return $res;
	}

	protected function genRow() {
		static $i = 0;
		return (object) array( 'id_field' => $i++ );
	}

	protected function mockDb() {
		// Cant mock from DatabaseType or DatabaseBase, they dont
		// have the full gamut of methods
		return $this->getMock( 'DatabaseMysql' );
	}
}

