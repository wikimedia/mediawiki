<?php
/* 
 * Tests for IP validity functions. Ported from /t/inc/IP.t by avar.
 */

class IPTest extends PHPUnit_Framework_TestCase {
	// not sure it should be tested with boolean false. hashar 20100924 
	public function testisIPAddress() {
		$this->assertFalse( IP::isIPAddress( false ), 'Boolean false is not an IP' );
		$this->assertFalse( IP::isIPAddress( true  ), 'Boolean true is not an IP' );
		$this->assertFalse( IP::isIPAddress( "" ), 'Empty string is not an IP' );
		$this->assertFalse( IP::isIPAddress( 'abc' ) );
		$this->assertFalse( IP::isIPAddress( ':' ) );
		$this->assertFalse( IP::isIPAddress( '2001:0DB8::A:1::1'), 'IPv6 with a double :: occurence' );
		$this->assertFalse( IP::isIPAddress( '2001:0DB8::A:1::'), 'IPv6 with a double :: occurence, last at end' );
		$this->assertFalse( IP::isIPAddress( '::2001:0DB8::5:1'), 'IPv6 with a double :: occurence, firt at beginning' );
		$this->assertFalse( IP::isIPAddress( '124.24.52' ), 'IPv4 not enough quads' );
		$this->assertFalse( IP::isIPAddress( '24.324.52.13' ), 'IPv4 out of range' );
		$this->assertFalse( IP::isIPAddress( '.24.52.13' ), 'IPv4 starts with period' );

		$this->assertTrue( IP::isIPAddress( 'fc:100::' ) );
		$this->assertTrue( IP::isIPAddress( 'fc:100:a:d:1:e:ac::' ) );
		$this->assertTrue( IP::isIPAddress( '::' ),  'RFC 4291 IPv6 Unspecified Address' );
		$this->assertTrue( IP::isIPAddress( '::1' ), 'RFC 4291 IPv6 Loopback Address' );
		$this->assertTrue( IP::isIPAddress( '::fc' ) );
		$this->assertTrue( IP::isIPAddress( '::fc:100:a:d:1:e:ac' ) );
		$this->assertTrue( IP::isIPAddress( 'fc::100' ) );
		$this->assertTrue( IP::isIPAddress( 'fc::100:a:d:1:e:ac' ) );
		$this->assertTrue( IP::isIPAddress( 'fc::100:a:d:1:e:ac/96', 'IPv6 range with "::"' ) );
		$this->assertTrue( IP::isIPAddress( 'fc:100:a:d:1:e:ac:0' ) );
		$this->assertTrue( IP::isIPAddress( 'fc:100:a:d:1:e:ac:0/24', 'IPv6 range' ) );
		$this->assertTrue( IP::isIPAddress( '124.24.52.13' ) );
		$this->assertTrue( IP::isIPAddress( '1.24.52.13' ) );
		$this->assertTrue( IP::isIPAddress( '74.24.52.13/20', 'IPv4 range' ) );
	}

	public function testisIPv6() {
		$this->assertFalse( IP::isIPv6( ':fc:100::' ), 'IPv6 starting with lone ":"' );
		$this->assertFalse( IP::isIPv6( 'fc:100:::' ), 'IPv6 ending with a ":::"' );
		$this->assertTrue( IP::isIPv6( 'fc:100::' ) );
		$this->assertTrue( IP::isIPv6( 'fc:100:a::' ) );
		$this->assertTrue( IP::isIPv6( 'fc:100:a:d::' ) );
		$this->assertTrue( IP::isIPv6( 'fc:100:a:d:1::' ) );
		$this->assertTrue( IP::isIPv6( 'fc:100:a:d:1:e::' ) );
		$this->assertTrue( IP::isIPv6( 'fc:100:a:d:1:e:ac::' ) );
		$this->assertFalse( IP::isIPv6( 'fc:100:a:d:1:e:ac:0::' ), 'IPv6 with 8 words ending with "::"' );
		$this->assertFalse( IP::isIPv6( 'fc:100:a:d:1:e:ac:0:1::' ), 'IPv6 with 9 words ending with "::"' );

		$this->assertFalse( IP::isIPv6( ':::' ) );
		$this->assertFalse( IP::isIPv6( '::0:' ), 'IPv6 ending in a lone ":"' );
		$this->assertTrue( IP::isIPv6( '::' ), 'IPv6 zero address' );
		$this->assertTrue( IP::isIPv6( '::0' ) );
		$this->assertTrue( IP::isIPv6( '::fc' ) );
		$this->assertTrue( IP::isIPv6( '::fc:100' ) );
		$this->assertTrue( IP::isIPv6( '::fc:100:a' ) );
		$this->assertTrue( IP::isIPv6( '::fc:100:a:d' ) );
		$this->assertTrue( IP::isIPv6( '::fc:100:a:d:1' ) );
		$this->assertTrue( IP::isIPv6( '::fc:100:a:d:1:e' ) );
		$this->assertTrue( IP::isIPv6( '::fc:100:a:d:1:e:ac' ) );
		$this->assertFalse( IP::isIPv6( '::fc:100:a:d:1:e:ac:0' ), 'IPv6 with "::" and 8 words' );
		$this->assertFalse( IP::isIPv6( '::fc:100:a:d:1:e:ac:0:1' ), 'IPv6 with 9 words' );

		$this->assertFalse( IP::isIPv6( ':fc::100' ), 'IPv6 starting with lone ":"' );
		$this->assertFalse( IP::isIPv6( 'fc::100:' ), 'IPv6 ending with lone ":"' );
		$this->assertFalse( IP::isIPv6( 'fc:::100' ), 'IPv6 with ":::" in the middle' );
		$this->assertTrue( IP::isIPv6( 'fc::100' ) );
		$this->assertTrue( IP::isIPv6( 'fc::100:a' ) );
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d' ) );
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d:1' ) );
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d:1:e' ) );
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d:1:e:ac' ) );
		$this->assertFalse( IP::isIPv6( 'fc::100:a:d:1:e:ac:0' ), 'IPv6 with "::" and 8 words' );
		$this->assertFalse( IP::isIPv6( 'fc::100:a:d:1:e:ac:0:1' ), 'IPv6 with 9 words' );

		$this->assertTrue( IP::isIPv6( 'fc:100:a:d:1:e:ac:0' ) );
	}

	public function testValidIPs() {
		foreach ( range( 0, 255 ) as $i ) {
			$a = sprintf( "%03d", $i );
			$b = sprintf( "%02d", $i );
			$c = sprintf( "%01d", $i );
			foreach ( array_unique( array( $a, $b, $c ) ) as $f ) {
				$ip = "$f.$f.$f.$f";
				$this->assertTrue( IP::isValid( $ip ) , "$ip is a valid IPv4 address" );
			}
		}
		foreach ( range( 0x0, 0xFFFF ) as $i ) {
			$a = sprintf( "%04x", $i );
			$b = sprintf( "%03x", $i );
			$c = sprintf( "%02x", $i );
			foreach ( array_unique( array( $a, $b, $c ) ) as $f ) {
				$ip = "$f:$f:$f:$f:$f:$f:$f:$f";
				$this->assertTrue( IP::isValid( $ip ) , "$ip is a valid IPv6 address" );
			}
		}
	}

	public function testInvalidIPs() {
		foreach ( range( 256, 999 ) as $i ) {
			$a = sprintf( "%03d", $i );
			$b = sprintf( "%02d", $i );
			$c = sprintf( "%01d", $i );
			foreach ( array_unique( array( $a, $b, $c ) ) as $f ) {
				$ip = "$f.$f.$f.$f";
				$this->assertFalse( IP::isValid( $ip ), "$ip is not a valid IPv4 address" );
			}
		}
		foreach ( range( 'g', 'z' ) as $i ) {
			$a = sprintf( "%04s", $i );
			$b = sprintf( "%03s", $i );
			$c = sprintf( "%02s", $i );
			foreach ( array_unique( array( $a, $b, $c ) ) as $f ) {
				$ip = "$f:$f:$f:$f:$f:$f:$f:$f";
				$this->assertFalse( IP::isValid( $ip ) , "$ip is not a valid IPv6 address" );
			}
		}
	}

	public function testBogusIPs() {
		$invalid = array(
			'www.xn--var-xla.net',
			'216.17.184.G',
			'216.17.184.1.',
			'216.17.184',
			'216.17.184.',
			'256.17.184.1'
		);
		foreach ( $invalid as $i ) {
			$this->assertFalse( IP::isValid( $i ), "$i is an invalid IPv4 address" );
		}
	}

	// test wrapper around ip2long which might return -1 or false depending on PHP version
	public function testip2longWrapper() {
		// fixme : add more tests ?
		$this->assertEquals( pow(2,32) - 1, IP::toUnsigned( '255.255.255.255' ));
		$i = 'IN.VA.LI.D';
		$this->assertFalse( IP::toUnSigned( $i ) );
	}

	public function testPrivateIPs() {
		$private = array( '10.0.0.1', '172.16.0.1', '192.168.0.1' );
		foreach ( $private as $p ) {
			$this->assertFalse( IP::isPublic( $p ), "$p is not a public IP address" );
		}
	}

	// Private wrapper used to test CIDR Parsing.
	private function assertFalseCIDR( $CIDR, $msg='' ) {
		$ff = array( false, false );
		$this->assertEquals( $ff, IP::parseCIDR( $CIDR ), $msg );
	}

	// Private wrapper to test network shifting using only dot notation
	private function assertNet( $expected, $CIDR ) {
		$parse = IP::parseCIDR( $CIDR );
		$this->assertEquals( $expected, long2ip( $parse[0] ), "network shifting $CIDR" );
	}

	public function testHexToQuad() {
		$this->assertEquals( '0.0.0.1'        , IP::hexToQuad( '00000001' ) );
		$this->assertEquals( '255.0.0.0'      , IP::hexToQuad( 'FF000000' ) );
		$this->assertEquals( '255.255.255.255', IP::hexToQuad( 'FFFFFFFF' ) );
		$this->assertEquals( '10.188.222.255' , IP::hexToQuad( '0ABCDEFF' ) );
		// hex not left-padded...
		$this->assertEquals( '0.0.0.0'     , IP::hexToQuad( '0' ) );
		$this->assertEquals( '0.0.0.1'     , IP::hexToQuad( '1' ) );
		$this->assertEquals( '0.0.0.255'   , IP::hexToQuad( 'FF' ) );
		$this->assertEquals( '0.0.255.0'   , IP::hexToQuad( 'FF00' ) );
	}

	public function testHexToOctet() {
		$this->assertEquals( '0:0:0:0:0:0:0:1',
			IP::hexToOctet( '00000000000000000000000000000001' ) );
		$this->assertEquals( '0:0:0:0:0:0:FF:3',
			IP::hexToOctet( '00000000000000000000000000FF0003' ) );
		$this->assertEquals( '0:0:0:0:0:0:FF00:6',
			IP::hexToOctet( '000000000000000000000000FF000006' ) );
		$this->assertEquals( '0:0:0:0:0:0:FCCF:FAFF',
			IP::hexToOctet( '000000000000000000000000FCCFFAFF' ) );
		$this->assertEquals( 'FFFF:FFFF:FFFF:FFFF:FFFF:FFFF:FFFF:FFFF',
			IP::hexToOctet( 'FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF' ) );
		// hex not left-padded...
		$this->assertEquals( '0:0:0:0:0:0:0:0'	 	, IP::hexToOctet( '0' ) );
		$this->assertEquals( '0:0:0:0:0:0:0:1'	 	, IP::hexToOctet( '1' ) );
		$this->assertEquals( '0:0:0:0:0:0:0:FF'  	, IP::hexToOctet( 'FF' ) );
		$this->assertEquals( '0:0:0:0:0:0:0:FFD0'	, IP::hexToOctet( 'FFD0' ) );
		$this->assertEquals( '0:0:0:0:0:0:FA00:0'	, IP::hexToOctet( 'FA000000' ) );
		$this->assertEquals( '0:0:0:0:0:0:FCCF:FAFF', IP::hexToOctet( 'FCCFFAFF' ) );
	}

	/*
	 * IP::parseCIDR() returns an array containing a signed IP address
	 * representing the network mask and the bit mask.
	 */
	function testCIDRParsing() {
		$this->assertFalseCIDR( '192.0.2.0' , "missing mask"    );	
		$this->assertFalseCIDR( '192.0.2.0/', "missing bitmask" );

		// Verify if statement
		$this->assertFalseCIDR( '256.0.0.0/32', "invalid net"      );
		$this->assertFalseCIDR( '192.0.2.0/AA', "mask not numeric" );
		$this->assertFalseCIDR( '192.0.2.0/-1', "mask < 0"         );
		$this->assertFalseCIDR( '192.0.2.0/33', "mask > 32"        );

		// Check internal logic
		# 0 mask always result in array(0,0)
		$this->assertEquals( array( 0, 0 ), IP::parseCIDR('192.0.0.2/0') );
		$this->assertEquals( array( 0, 0 ), IP::parseCIDR('0.0.0.0/0') );
		$this->assertEquals( array( 0, 0 ), IP::parseCIDR('255.255.255.255/0') );

		// FIXME : add more tests.
		
		# This part test network shifting
		$this->assertNet( '192.0.0.0'  , '192.0.0.2/24'   );
		$this->assertNet( '192.168.5.0', '192.168.5.13/24');
		$this->assertNet( '10.0.0.160' , '10.0.0.161/28'  );
		$this->assertNet( '10.0.0.0'   , '10.0.0.3/28'  );
		$this->assertNet( '10.0.0.0'   , '10.0.0.3/30'  );
		$this->assertNet( '10.0.0.4'   , '10.0.0.4/30'  );
		$this->assertNet( '172.17.32.0', '172.17.35.48/21' );
		$this->assertNet( '10.128.0.0' , '10.135.0.0/9' );
		$this->assertNet( '134.0.0.0'  , '134.0.5.1/8'  ); 
	}
}
