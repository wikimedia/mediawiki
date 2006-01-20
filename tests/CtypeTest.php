<?php
require_once 'PHPUnit.php';

class CtypeTest extends PHPUnit_TestCase {
	var $functions;
	
	function CtypeTest( $name ) {
		$this->PHPUnit_TestCase( $name );
	}

	function setUp() {
		$cont = file_get_contents( '../includes/compatability/ctype.php' );

		preg_match_all( '~function (ctype_[a-z]+)~', $cont, $m );
		$this->populateFunctions( $m[1] );

		// Will get called before each test* function
		if ( function_exists( '_ctype_alnum' ) )
			return;
		else {
			$cont = preg_replace( '~^<\?php~', '', $cont );
			$cont = preg_replace( '~\?>$~', '', $cont );

			// Rename the custom functions so they don't conflict
			$cont = preg_replace( '~(function )(ctype_)~', '\1_\2', $cont );

			eval( $cont );
		}
	}

	function populateFunctions( $functions ) {
		$this->functions = array();
		foreach ( $functions as $function )
			$this->functions[$function] = "_$function";
	}
	
	function tearDown() {}

//	function testInteger256_to_big() {}

	function testInteger0_to_255() {
		foreach ( $this->functions as $phpfunc => $mwfunc )
			foreach ( range( 0, 255 ) as $i )
				$this->assertEquals(
					_ctype_alnum( $i ),
					ctype_alnum( $i ),
					"On $i ($phpfunc)"
				);
	}

	function testChr0_to_255() {
		foreach ( $this->functions as $phpfunc => $mwfunc )
			foreach ( range( 0, 255 ) as $i ) {
				$i = chr( $i );
				$this->assertEquals(
					_ctype_alnum( $i ),
					ctype_alnum( $i ),
					"On $i ($phpfunc)"
				);
			}
	}

}
?>
