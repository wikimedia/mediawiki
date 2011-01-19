<?php
/**
 * This come from r75429 message
 * @author Platonides 
 */

require_once( dirname( __FILE__ ) . '/Benchmarker.php' );
class bench_HTTP_HTTPS extends Benchmarker {

	public function __construct() {
		parent::__construct();	
	}

	public function execute() {
		$this->bench( array(
			array( 'function' => array( $this, 'getHTTP' ) ),
			array( 'function' => array( $this, 'getHTTPS' ) ),
		));
		print $this->getFormattedResults();
	}

	static function doRequest( $proto ) {
		Http::get( "$proto://localhost/" );
	}

	// bench function 1
	function getHTTP() {
		$this->doRequest( 'http' );
	}

	// bench function 2
	function getHTTPS() {
		$this->doRequest( 'https' );
	}
}

$maintClass = 'bench_HTTP_HTTPS';
require_once( RUN_MAINTENANCE_IF_MAIN );
