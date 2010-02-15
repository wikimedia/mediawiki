<?php

/**
 * Test the CDB reader/writer
 */

class CdbTest extends PHPUnit_Framework_TestCase {

	public function setup() {
		if ( !CdbReader::haveExtension() ) {
			$this->markTestIncomplete( 'This test requires native CDB support to be present.' );
		}
	}

	public function testCdb() {
		$w1 = new CdbWriter_PHP( 'php.cdb' );
		$w2 = new CdbWriter_DBA( 'dba.cdb' );

		$data = array();
		for ( $i = 0; $i < 1000; $i++ ) {
			$key = $this->randomString();
			$value = $this->randomString();
			$w1->set( $key, $value );
			$w2->set( $key, $value );

			if ( !isset( $data[$key] ) ) {
				$data[$key] = $value;
			}
		}

		$w1->close();
		$w2->close();

		$this->assertEquals(
			md5_file( 'dba.cdb' ),
			md5_file( 'php.cdb' ),
			'same hash'
		);

		$r1 = new CdbReader_PHP( 'php.cdb' );
		$r2 = new CdbReader_DBA( 'dba.cdb' );

		foreach ( $data as $key => $value ) {
			if ( $key === '' ) {
				// Known bug
				continue;
			}
			$v1 = $r1->get( $key );
			$v2 = $r2->get( $key );

			$v1 = $v1 === false ? '(not found)' : $v1;
			$v2 = $v2 === false ? '(not found)' : $v2;

			#cdbAssert( 'Mismatch', $key, $v1, $v2 );
			$this->cdbAssert( "PHP error", $key, $v1, $value );
			$this->cdbAssert( "DBA error", $key, $v2, $value );
		}

		unlink( 'dba.cdb' );
		unlink( 'php.cdb' );
	}

	private function randomString() {
		$len = mt_rand( 0, 10 );
		$s = '';
		for ( $j = 0; $j < $len; $j++ ) {
			$s .= chr( mt_rand( 0, 255 ) );
		}
		return $s;
	}

	private function cdbAssert( $msg, $key, $v1, $v2 ) {
		$this->assertEquals(
			$v2,
			$v1,
			$msg . ', k=' . bin2hex( $key )
		);
	}
}
