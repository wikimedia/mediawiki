<?php
/**
 * @covers ::wfBaseConvert
 */
class WfBaseConvertTest extends MediaWikiTestCase {
	public static function provideSingleDigitConversions() {
		return array(
			//      2    3    5    8   10   16   36
			array( '0', '0', '0', '0', '0', '0', '0' ),
			array( '1', '1', '1', '1', '1', '1', '1' ),
			array( '10', '2', '2', '2', '2', '2', '2' ),
			array( '11', '10', '3', '3', '3', '3', '3' ),
			array( '100', '11', '4', '4', '4', '4', '4' ),
			array( '101', '12', '10', '5', '5', '5', '5' ),
			array( '110', '20', '11', '6', '6', '6', '6' ),
			array( '111', '21', '12', '7', '7', '7', '7' ),
			array( '1000', '22', '13', '10', '8', '8', '8' ),
			array( '1001', '100', '14', '11', '9', '9', '9' ),
			array( '1010', '101', '20', '12', '10', 'a', 'a' ),
			array( '1011', '102', '21', '13', '11', 'b', 'b' ),
			array( '1100', '110', '22', '14', '12', 'c', 'c' ),
			array( '1101', '111', '23', '15', '13', 'd', 'd' ),
			array( '1110', '112', '24', '16', '14', 'e', 'e' ),
			array( '1111', '120', '30', '17', '15', 'f', 'f' ),
			array( '10000', '121', '31', '20', '16', '10', 'g' ),
			array( '10001', '122', '32', '21', '17', '11', 'h' ),
			array( '10010', '200', '33', '22', '18', '12', 'i' ),
			array( '10011', '201', '34', '23', '19', '13', 'j' ),
			array( '10100', '202', '40', '24', '20', '14', 'k' ),
			array( '10101', '210', '41', '25', '21', '15', 'l' ),
			array( '10110', '211', '42', '26', '22', '16', 'm' ),
			array( '10111', '212', '43', '27', '23', '17', 'n' ),
			array( '11000', '220', '44', '30', '24', '18', 'o' ),
			array( '11001', '221', '100', '31', '25', '19', 'p' ),
			array( '11010', '222', '101', '32', '26', '1a', 'q' ),
			array( '11011', '1000', '102', '33', '27', '1b', 'r' ),
			array( '11100', '1001', '103', '34', '28', '1c', 's' ),
			array( '11101', '1002', '104', '35', '29', '1d', 't' ),
			array( '11110', '1010', '110', '36', '30', '1e', 'u' ),
			array( '11111', '1011', '111', '37', '31', '1f', 'v' ),
			array( '100000', '1012', '112', '40', '32', '20', 'w' ),
			array( '100001', '1020', '113', '41', '33', '21', 'x' ),
			array( '100010', '1021', '114', '42', '34', '22', 'y' ),
			array( '100011', '1022', '120', '43', '35', '23', 'z' )
		);
	}

	/**
	 * @dataProvider provideSingleDigitConversions
	 */
	public function testDigitToBase2( $base2, $base3, $base5, $base8, $base10, $base16, $base36 ) {
		$this->assertSame( $base2, wfBaseConvert( $base3, '3', '2' ) );
		$this->assertSame( $base2, wfBaseConvert( $base5, '5', '2' ) );
		$this->assertSame( $base2, wfBaseConvert( $base8, '8', '2' ) );
		$this->assertSame( $base2, wfBaseConvert( $base10, '10', '2' ) );
		$this->assertSame( $base2, wfBaseConvert( $base16, '16', '2' ) );
		$this->assertSame( $base2, wfBaseConvert( $base36, '36', '2' ) );
	}

	/**
	 * @dataProvider provideSingleDigitConversions
	 */
	public function testDigitToBase3( $base2, $base3, $base5, $base8, $base10, $base16, $base36 ) {
		$this->assertSame( $base3, wfBaseConvert( $base2, '2', '3' ) );
		$this->assertSame( $base3, wfBaseConvert( $base5, '5', '3' ) );
		$this->assertSame( $base3, wfBaseConvert( $base8, '8', '3' ) );
		$this->assertSame( $base3, wfBaseConvert( $base10, '10', '3' ) );
		$this->assertSame( $base3, wfBaseConvert( $base16, '16', '3' ) );
		$this->assertSame( $base3, wfBaseConvert( $base36, '36', '3' ) );
	}

	/**
	 * @dataProvider provideSingleDigitConversions
	 */
	public function testDigitToBase5( $base2, $base3, $base5, $base8, $base10, $base16, $base36 ) {
		$this->assertSame( $base5, wfBaseConvert( $base2, '2', '5' ) );
		$this->assertSame( $base5, wfBaseConvert( $base3, '3', '5' ) );
		$this->assertSame( $base5, wfBaseConvert( $base8, '8', '5' ) );
		$this->assertSame( $base5, wfBaseConvert( $base10, '10', '5' ) );
		$this->assertSame( $base5, wfBaseConvert( $base16, '16', '5' ) );
		$this->assertSame( $base5, wfBaseConvert( $base36, '36', '5' ) );
	}

	/**
	 * @dataProvider provideSingleDigitConversions
	 */
	public function testDigitToBase8( $base2, $base3, $base5, $base8, $base10, $base16, $base36 ) {
		$this->assertSame( $base8, wfBaseConvert( $base2, '2', '8' ) );
		$this->assertSame( $base8, wfBaseConvert( $base3, '3', '8' ) );
		$this->assertSame( $base8, wfBaseConvert( $base5, '5', '8' ) );
		$this->assertSame( $base8, wfBaseConvert( $base10, '10', '8' ) );
		$this->assertSame( $base8, wfBaseConvert( $base16, '16', '8' ) );
		$this->assertSame( $base8, wfBaseConvert( $base36, '36', '8' ) );
	}

	/**
	 * @dataProvider provideSingleDigitConversions
	 */
	public function testDigitToBase10( $base2, $base3, $base5, $base8, $base10, $base16, $base36 ) {
		$this->assertSame( $base10, wfBaseConvert( $base2, '2', '10' ) );
		$this->assertSame( $base10, wfBaseConvert( $base3, '3', '10' ) );
		$this->assertSame( $base10, wfBaseConvert( $base5, '5', '10' ) );
		$this->assertSame( $base10, wfBaseConvert( $base8, '8', '10' ) );
		$this->assertSame( $base10, wfBaseConvert( $base16, '16', '10' ) );
		$this->assertSame( $base10, wfBaseConvert( $base36, '36', '10' ) );
	}

	/**
	 * @dataProvider provideSingleDigitConversions
	 */
	public function testDigitToBase16( $base2, $base3, $base5, $base8, $base10, $base16, $base36 ) {
		$this->assertSame( $base16, wfBaseConvert( $base2, '2', '16' ) );
		$this->assertSame( $base16, wfBaseConvert( $base3, '3', '16' ) );
		$this->assertSame( $base16, wfBaseConvert( $base5, '5', '16' ) );
		$this->assertSame( $base16, wfBaseConvert( $base8, '8', '16' ) );
		$this->assertSame( $base16, wfBaseConvert( $base10, '10', '16' ) );
		$this->assertSame( $base16, wfBaseConvert( $base36, '36', '16' ) );
	}

	/**
	 * @dataProvider provideSingleDigitConversions
	 */
	public function testDigitToBase36( $base2, $base3, $base5, $base8, $base10, $base16, $base36 ) {
		$this->assertSame( $base36, wfBaseConvert( $base2, '2', '36' ) );
		$this->assertSame( $base36, wfBaseConvert( $base3, '3', '36' ) );
		$this->assertSame( $base36, wfBaseConvert( $base5, '5', '36' ) );
		$this->assertSame( $base36, wfBaseConvert( $base8, '8', '36' ) );
		$this->assertSame( $base36, wfBaseConvert( $base10, '10', '36' ) );
		$this->assertSame( $base36, wfBaseConvert( $base16, '16', '36' ) );
	}

	public function testLargeNumber() {
		$this->assertSame( '1100110001111010000000101110100', wfBaseConvert( 'sd89ys', 36, 2 ) );
		$this->assertSame( '11102112120221201101', wfBaseConvert( 'sd89ys', 36, 3 ) );
		$this->assertSame( '12003102232400', wfBaseConvert( 'sd89ys', 36, 5 ) );
		$this->assertSame( '14617200564', wfBaseConvert( 'sd89ys', 36, 8 ) );
		$this->assertSame( '1715274100', wfBaseConvert( 'sd89ys', 36, 10 ) );
		$this->assertSame( '663d0174', wfBaseConvert( 'sd89ys', 36, 16 ) );
	}

	public static function provideNumbers() {
		$x = array();
		$chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		for ( $i = 0; $i < 50; $i++ ) {
			$base = mt_rand( 2, 36 );
			$len = mt_rand( 10, 100 );

			$str = '';
			for ( $j = 0; $j < $len; $j++ ) {
				$str .= $chars[mt_rand( 0, $base - 1 )];
			}

			$x[] = array( $base, $str );
		}

		return $x;
	}

	/**
	 * @dataProvider provideNumbers
	 */
	public function testIdentity( $base, $number ) {
		$this->assertSame( $number, wfBaseConvert( $number, $base, $base, strlen( $number ) ) );
	}

	public function testInvalid() {
		$this->assertFalse( wfBaseConvert( '101', 1, 15 ) );
		$this->assertFalse( wfBaseConvert( '101', 15, 1 ) );
		$this->assertFalse( wfBaseConvert( '101', 37, 15 ) );
		$this->assertFalse( wfBaseConvert( '101', 15, 37 ) );
		$this->assertFalse( wfBaseConvert( 'abcde', 10, 11 ) );
		$this->assertFalse( wfBaseConvert( '12930', 2, 10 ) );
		$this->assertFalse( wfBaseConvert( '101', 'abc', 15 ) );
		$this->assertFalse( wfBaseConvert( '101', 15, 'abc' ) );
	}

	public function testPadding() {
		$number = "10101010101";
		$this->assertSame( strlen( $number ) + 5, strlen( wfBaseConvert( $number, 2, 2, strlen( $number ) + 5 ) ) );
		$this->assertSame( strlen( $number ), strlen( wfBaseConvert( $number, 2, 2, strlen( $number ) - 5 ) ) );
	}
}
