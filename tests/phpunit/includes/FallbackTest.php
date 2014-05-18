<?php

/**
 * @covers Fallback
 */
class FallbackTest extends MediaWikiTestCase {
	public function testFallbackMbstringFunctions() {
		if ( !extension_loaded( 'mbstring' ) ) {
			$this->markTestSkipped(
				"The mb_string functions must be installed to test the fallback functions"
			);
		}

		$sampleUTF = "Östergötland_coat_of_arms.png";

		//mb_substr
		$substr_params = array(
			array( 0, 0 ),
			array( 5, -4 ),
			array( 33 ),
			array( 100, -5 ),
			array( -8, 10 ),
			array( 1, 1 ),
			array( 2, -1 )
		);

		foreach ( $substr_params as $param_set ) {
			$old_param_set = $param_set;
			array_unshift( $param_set, $sampleUTF );

			$this->assertEquals(
				call_user_func_array( 'mb_substr', $param_set ),
				call_user_func_array( 'Fallback::mb_substr', $param_set ),
				'Fallback mb_substr with params ' . implode( ', ', $old_param_set )
			);
		}

		//mb_strlen
		$this->assertEquals(
			mb_strlen( $sampleUTF ),
			Fallback::mb_strlen( $sampleUTF ),
			'Fallback mb_strlen'
		);

		//mb_str(r?)pos
		$strpos_params = array(
			//array( 'ter' ),
			//array( 'Ö' ),
			//array( 'Ö', 3 ),
			//array( 'oat_', 100 ),
			//array( 'c', -10 ),
			//Broken for now
		);

		foreach ( $strpos_params as $param_set ) {
			$old_param_set = $param_set;
			array_unshift( $param_set, $sampleUTF );

			$this->assertEquals(
				call_user_func_array( 'mb_strpos', $param_set ),
				call_user_func_array( 'Fallback::mb_strpos', $param_set ),
				'Fallback mb_strpos with params ' . implode( ', ', $old_param_set )
			);

			$this->assertEquals(
				call_user_func_array( 'mb_strrpos', $param_set ),
				call_user_func_array( 'Fallback::mb_strrpos', $param_set ),
				'Fallback mb_strrpos with params ' . implode( ', ', $old_param_set )
			);
		}
	}
}
