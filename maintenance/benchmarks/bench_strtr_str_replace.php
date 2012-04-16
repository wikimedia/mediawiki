<?php
/**
 * @file
 * @ingroup Benchmark
 */

require_once( dirname( __FILE__ ) . '/Benchmarker.php' );

function bfNormalizeTitleStrTr( $str ) {
    return strtr( $str, '_', ' ' );
}

function bfNormalizeTitleStrReplace( $str ) {
    return str_replace( '_', ' ', $str );
}

class bench_strtr_str_replace extends Benchmarker {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Benchmark for strtr() vs str_replace().";
	}

	public function execute() {
		$this->bench( array(
			array( 'function' => array( $this, 'benchstrtr' ) ),
			array( 'function' => array( $this, 'benchstr_replace' ) ),
			array( 'function' => array( $this, 'benchstrtr_indirect' ) ),
			array( 'function' => array( $this, 'benchstr_replace_indirect' ) ),
		));
		print $this->getFormattedResults();
	}

	function benchstrtr() {
		strtr( "[[MediaWiki:Some_random_test_page]]", "_", " " );
	}

	function benchstr_replace() {
		str_replace( "_", " ", "[[MediaWiki:Some_random_test_page]]");
	}


	function benchstrtr_indirect() {
		bfNormalizeTitleStrTr( "[[MediaWiki:Some_random_test_page]]" );
	}

	function benchstr_replace_indirect() {
		bfNormalizeTitleStrReplace( "[[MediaWiki:Some_random_test_page]]" );
	}

}

$maintClass = 'bench_strtr_str_replace';
require_once( RUN_MAINTENANCE_IF_MAIN );
