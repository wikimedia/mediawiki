<?php

#ini_set( 'memory_limit', '40M' );

require_once( 'PHPUnit.php' );
require_once( 'UtfNormal.php' );

class CleanUpTest extends PHPUnit_TestCase {
	function CleanUpTest( $name ) {
		$this->PHPUnit_TestCase( $name );
	}

	function setUp() {
	}
	
	function tearDown() {
	}
	
	function testAscii() {
		$text = 'This is plain ASCII text.';
		$this->assertEquals( $text, UtfNormal::cleanUp( $text ) );
	}
	
	function testNull() {
		$text = "a \x00 null";
		$expect = "a \xef\xbf\xbd null";
		$this->assertEquals(
			bin2hex( $expect ),
			bin2hex( UtfNormal::cleanUp( $text ) ) );
	}
	
	function testLatin() {
		$text = "L'\xc3\xa9cole";
		$this->assertEquals( $text, UtfNormal::cleanUp( $text ) );
	}
	
	function testLatinNormal() {
		$text = "L'e\xcc\x81cole";
		$expect = "L'\xc3\xa9cole";
		$this->assertEquals( $expect, UtfNormal::cleanUp( $text ) );
	}
	
	# This test is *very* expensive!
	function XtestAllChars() {
		$rep = UTF8_REPLACEMENT;
		global $utfCanonicalComp, $utfCanonicalDecomp;
		for( $i = 0x0; $i < UNICODE_MAX; $i++ ) {
			$char = codepointToUtf8( $i );
			$clean = UtfNormal::cleanUp( $char );
			$x = sprintf( "%04X", $i );
			if( $i % 0x1000 == 0 ) echo "U+$x\n";
			if( $i == 0x0009 ||
			    $i == 0x000a ||
			    $i == 0x000d ||
			    ($i > 0x001f && $i < UNICODE_SURROGATE_FIRST) ||
			    ($i > UNICODE_SURROGATE_LAST && $i < 0xfdd0 ) ||
			    ($i > 0xfdef && $i < 0xfffe ) ||
			    ($i > 0xffff && $i <= UNICODE_MAX ) ) {
				if( isset( $utfCanonicalComp[$char] ) || isset( $utfCanonicalDecomp[$char] ) ) {
				    $comp = UtfNormal::NFC( $char );
					$this->assertEquals(
						bin2hex( $comp ),
						bin2hex( $clean ),
						"U+$x should be decomposed" );
				} else {
					$this->assertEquals(
						bin2hex( $char ), 
						bin2hex( $clean ),
						"U+$x should be intact" );
				}
			} else {
				$this->assertEquals( bin2hex( $rep ), bin2hex( $clean ), $x );
			}
		}
	}
	
	function testAllBytes() {
		$this->doTestBytes( '', '' );
		$this->doTestBytes( 'x', '' );
		$this->doTestBytes( '', 'x' );
		$this->doTestBytes( 'x', 'x' );
	}
	
	function doTestBytes( $head, $tail ) {
		for( $i = 0x0; $i < 256; $i++ ) {
			$char = $head . chr( $i ) . $tail;
			$clean = UtfNormal::cleanUp( $char );
			$x = sprintf( "%02X", $i );
			if( $i == 0x0009 ||
			    $i == 0x000a ||
			    $i == 0x000d ||
			    ($i > 0x001f && $i < 0x80) ) {
				$this->assertEquals(
					bin2hex( $char ), 
					bin2hex( $clean ),
					"ASCII byte $x should be intact" );
			} else {
				$this->assertEquals(
					bin2hex( $head . UTF8_REPLACEMENT . $tail ),
					bin2hex( $clean ),
					"Forbidden byte $x should be rejected" );
			}
		}
	}
	
	function testDoubleBytes() {
		$this->doTestDoubleBytes( '', '' );
		$this->doTestDoubleBytes( 'x', '' );
		$this->doTestDoubleBytes( '', 'x' );
		$this->doTestDoubleBytes( 'x', 'x' );
	}
	
	function doTestDoubleBytes( $head, $tail ) {
		for( $first = 0xc0; $first < 0x100; $first++ ) {
			for( $second = 0x80; $second < 0x100; $second++ ) {
				$char = $head . chr( $first ) . chr( $second ) . $tail;
				$clean = UtfNormal::cleanUp( $char );
				$x = sprintf( "%02X,%02X", $first, $second );
				if( $first > 0xc1 &&
				    $first < 0xe0 &&
				    $second < 0xc0 ) {
					$this->assertEquals(
						bin2hex( UtfNormal::NFC( $char ) ), 
						bin2hex( $clean ),
						"Pair $x should be intact" );
				} elseif( $first > 0xfd || $second > 0xbf ) {
					# fe and ff are not legal head bytes -- expect two replacement chars
					$this->assertEquals(
						bin2hex( $head . UTF8_REPLACEMENT . UTF8_REPLACEMENT . $tail ),
						bin2hex( $clean ),
						"Forbidden pair $x should be rejected" );
				} else {
					$this->assertEquals(
						bin2hex( $head . UTF8_REPLACEMENT . $tail ),
						bin2hex( $clean ),
						"Forbidden pair $x should be rejected" );
				}
			}
		}
	}

	function testTripleBytes() {
		$this->doTestTripleBytes( '', '' );
		#$this->doTestTripleBytes( 'x', '' );
		#$this->doTestTripleBytes( '', 'x' );
		#$this->doTestTripleBytes( 'x', 'x' );
	}
	
	function doTestTripleBytes( $head, $tail ) {
		for( $first = 0xc0; $first < 0x100; $first++ ) {
			for( $second = 0x80; $second < 0x100; $second++ ) {
				#for( $third = 0x80; $third < 0x100; $third++ ) {
				for( $third = 0x80; $third < 0x81; $third++ ) {
					$char = $head . chr( $first ) . chr( $second ) . chr( $third ) . $tail;
					$clean = UtfNormal::cleanUp( $char );
					$x = sprintf( "%02X,%02X,%02X", $first, $second, $third );
					if( $first >= 0xe0 &&
						$first < 0xf0 &&
						$second < 0xc0 &&
						$third < 0xc0 ) {
						if( $first == 0xe0 && $second < 0xa0 ) {
							$this->assertEquals(
								bin2hex( UTF8_REPLACEMENT ), 
								bin2hex( $clean ),
								"Overlong triplet $x should be rejected" );
						} elseif( $first == 0xed && 
							( chr( $first ) . chr( $second ) . chr( $third ))  >= UTF8_SURROGATE_FIRST ) {
							$this->assertEquals(
								bin2hex( UTF8_REPLACEMENT ), 
								bin2hex( $clean ),
								"Surrogate triplet $x should be rejected" );
						} else {
							$this->assertEquals(
								bin2hex( UtfNormal::NFC( $char ) ), 
								bin2hex( $clean ),
								"Triplet $x should be intact" );
						}
					} elseif( $first > 0xc1 && $first < 0xe0 && $second < 0xc0 ) {
						$this->assertEquals(
							bin2hex( $head . UtfNormal::NFC( chr( $first ) . chr( $second ) ) . UTF8_REPLACEMENT . $tail ),
							bin2hex( $clean ),
							"Valid 2-byte $x + broken tail" );
					} elseif( $second > 0xc1 && $second < 0xe0 && $third < 0xc0 ) {
						$this->assertEquals(
							bin2hex( $head . UTF8_REPLACEMENT . UtfNormal::NFC( chr( $second ) . chr( $third ) ) . $tail ),
							bin2hex( $clean ),
							"Broken head + valid 2-byte $x" );
					} elseif( $first > 0xfd && ( ( $second > 0xbf && $third > 0xbf ) || ($second < 0xc0 && $third < 0xc0 ) || ($second > 0xfd ) || ($third > 0xfd) ) ) {
						# fe and ff are not legal head bytes -- expect three replacement chars
						$this->assertEquals(
							bin2hex( $head . UTF8_REPLACEMENT . UTF8_REPLACEMENT . UTF8_REPLACEMENT . $tail ),
							bin2hex( $clean ),
							"Forbidden triplet $x should be rejected" );
					} elseif( $second < 0xc0 && $second < 0xc0 ) {
						$this->assertEquals(
							bin2hex( $head . UTF8_REPLACEMENT . $tail ),
							bin2hex( $clean ),
							"Forbidden triplet $x should be rejected" );
					} else {
						$this->assertEquals(
							bin2hex( $head . UTF8_REPLACEMENT . UTF8_REPLACEMENT . $tail ),
							bin2hex( $clean ),
							"Forbidden triplet $x should be rejected" );
					}
				}
			}
		}
	}

}


$suite =& new PHPUnit_TestSuite( 'CleanUpTest' );
$result = PHPUnit::run( $suite );
echo $result->toString();

?>