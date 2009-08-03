<?php

/**
 * Test the CDB reader/writer
 */

require_once( dirname(__FILE__) . '/Maintenance.php' );

class CdbTest extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "CDB read/write test";
	}

	public function execute() {
		$this->output( "Write test...\n" );

		$w1 = new CdbWriter_PHP( 'php.cdb' );
		$w2 = new CdbWriter_DBA( 'dba.cdb' );

		$data = array();
		for ( $i = 0; $i < 100000; $i++ ) {
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

		passthru( 'md5sum php.cdb dba.cdb' );

		$this->output( "Read test...\n" );

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
		$this->output( "Done.\n" );
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
		if ( $v1 !== $v2 ) {
			$this->output( $msg . ', k=' . bin2hex( $key ) . 
				', v1=' . bin2hex( $v1 ) . 
				', v2=' . bin2hex( $v2 ) . "\n" );
			return false;
		} else {
			return true;
		}
	}
}

$maintClass = "CdbTest";
require_once( DO_MAINTENANCE );
