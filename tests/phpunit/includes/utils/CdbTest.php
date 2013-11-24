<?php

/**
 * Test the CDB reader/writer
 * @covers CdbWriterPHP
 * @covers CdbWriterDBA
 */
class CdbTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		if ( !CdbReader::haveExtension() ) {
			$this->markTestSkipped( 'Native CDB support is not available' );
		}
	}

	/**
	 * @group medium
	 */
	public function testCdb() {
		$dir = wfTempDir();
		if ( !is_writable( $dir ) ) {
			$this->markTestSkipped( "Temp dir isn't writable" );
		}

		$phpcdbfile = $this->getNewTempFile();
		$dbacdbfile = $this->getNewTempFile();

		$w1 = new CdbWriterPHP( $phpcdbfile );
		$w2 = new CdbWriterDBA( $dbacdbfile );

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
			md5_file( $phpcdbfile ),
			md5_file( $dbacdbfile ),
			'same hash'
		);

		$r1 = new CdbReaderPHP( $phpcdbfile );
		$r2 = new CdbReaderDBA( $dbacdbfile );

		foreach ( $data as $key => $value ) {
			if ( $key === '' ) {
				// Known bug
				continue;
			}
			$v1 = $r1->get( $key );
			$v2 = $r2->get( $key );

			$v1 = $v1 === false ? '(not found)' : $v1;
			$v2 = $v2 === false ? '(not found)' : $v2;

			# cdbAssert( 'Mismatch', $key, $v1, $v2 );
			$this->cdbAssert( "PHP error", $key, $v1, $value );
			$this->cdbAssert( "DBA error", $key, $v2, $value );
		}
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
