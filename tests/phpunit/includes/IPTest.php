<?php
/**
 * Tests for IP validity functions. Ported from /t/inc/IP.t by avar.
 * @group IP
 */

class IPTest extends MediaWikiTestCase {
	/**
	 *  not sure it should be tested with boolean false. hashar 20100924
	 * @covers IP::isIPAddress
	 */
	public function testisIPAddress() {
		$this->assertFalse( IP::isIPAddress( false ), 'Boolean false is not an IP' );
		$this->assertFalse( IP::isIPAddress( true  ), 'Boolean true is not an IP' );
		$this->assertFalse( IP::isIPAddress( "" ), 'Empty string is not an IP' );
		$this->assertFalse( IP::isIPAddress( 'abc' ), 'Garbage IP string' );
		$this->assertFalse( IP::isIPAddress( ':' ), 'Single ":" is not an IP' );
		$this->assertFalse( IP::isIPAddress( '2001:0DB8::A:1::1'), 'IPv6 with a double :: occurrence' );
		$this->assertFalse( IP::isIPAddress( '2001:0DB8::A:1::'), 'IPv6 with a double :: occurrence, last at end' );
		$this->assertFalse( IP::isIPAddress( '::2001:0DB8::5:1'), 'IPv6 with a double :: occurrence, firt at beginning' );
		$this->assertFalse( IP::isIPAddress( '124.24.52' ), 'IPv4 not enough quads' );
		$this->assertFalse( IP::isIPAddress( '24.324.52.13' ), 'IPv4 out of range' );
		$this->assertFalse( IP::isIPAddress( '.24.52.13' ), 'IPv4 starts with period' );
		$this->assertFalse( IP::isIPAddress( 'fc:100:300' ), 'IPv6 with only 3 words' );

		$this->assertTrue( IP::isIPAddress( '::' ), 'RFC 4291 IPv6 Unspecified Address' );
		$this->assertTrue( IP::isIPAddress( '::1' ), 'RFC 4291 IPv6 Loopback Address' );
		$this->assertTrue( IP::isIPAddress( '74.24.52.13/20', 'IPv4 range' ) );
		$this->assertTrue( IP::isIPAddress( 'fc:100:a:d:1:e:ac:0/24' ), 'IPv6 range' );
		$this->assertTrue( IP::isIPAddress( 'fc::100:a:d:1:e:ac/96' ), 'IPv6 range with "::"' );

		$validIPs = array( 'fc:100::', 'fc:100:a:d:1:e:ac::', 'fc::100', '::fc:100:a:d:1:e:ac',
			'::fc', 'fc::100:a:d:1:e:ac', 'fc:100:a:d:1:e:ac:0', '124.24.52.13', '1.24.52.13' );
		foreach ( $validIPs as $ip ) {
			$this->assertTrue( IP::isIPAddress( $ip ), "$ip is a valid IP address" );
		}
	}

	/**
	 * @covers IP::isIPv6
	 */
	public function testisIPv6() {
		$this->assertFalse( IP::isIPv6( ':fc:100::' ), 'IPv6 starting with lone ":"' );
		$this->assertFalse( IP::isIPv6( 'fc:100:::' ), 'IPv6 ending with a ":::"' );
		$this->assertFalse( IP::isIPv6( 'fc:300' ), 'IPv6 with only 2 words' );
		$this->assertFalse( IP::isIPv6( 'fc:100:300' ), 'IPv6 with only 3 words' );

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

		$this->assertTrue( IP::isIPv6( 'fc::100' ), 'IPv6 with "::" and 2 words' );
		$this->assertTrue( IP::isIPv6( 'fc::100:a' ), 'IPv6 with "::" and 3 words' );
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d', 'IPv6 with "::" and 4 words' ) );
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d:1' ), 'IPv6 with "::" and 5 words' );
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d:1:e' ), 'IPv6 with "::" and 6 words' );
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d:1:e:ac' ), 'IPv6 with "::" and 7 words' );
		$this->assertTrue( IP::isIPv6( '2001::df'), 'IPv6 with "::" and 2 words' );
		$this->assertTrue( IP::isIPv6( '2001:5c0:1400:a::df'), 'IPv6 with "::" and 5 words' );
		$this->assertTrue( IP::isIPv6( '2001:5c0:1400:a::df:2'), 'IPv6 with "::" and 6 words' );

		$this->assertFalse( IP::isIPv6( 'fc::100:a:d:1:e:ac:0' ), 'IPv6 with "::" and 8 words' );
		$this->assertFalse( IP::isIPv6( 'fc::100:a:d:1:e:ac:0:1' ), 'IPv6 with 9 words' );

		$this->assertTrue( IP::isIPv6( 'fc:100:a:d:1:e:ac:0' ) );
	}

	/**
	 * @covers IP::isIPv4
	 */
	public function testisIPv4() {
		$this->assertFalse( IP::isIPv4( false ), 'Boolean false is not an IP' );
		$this->assertFalse( IP::isIPv4( true  ), 'Boolean true is not an IP' );
		$this->assertFalse( IP::isIPv4( "" ), 'Empty string is not an IP' );
		$this->assertFalse( IP::isIPv4( 'abc' ) );
		$this->assertFalse( IP::isIPv4( ':' ) );
		$this->assertFalse( IP::isIPv4( '124.24.52' ), 'IPv4 not enough quads' );
		$this->assertFalse( IP::isIPv4( '24.324.52.13' ), 'IPv4 out of range' );
		$this->assertFalse( IP::isIPv4( '.24.52.13' ), 'IPv4 starts with period' );

		$this->assertTrue( IP::isIPv4( '124.24.52.13' ) );
		$this->assertTrue( IP::isIPv4( '1.24.52.13' ) );
		$this->assertTrue( IP::isIPv4( '74.24.52.13/20', 'IPv4 range' ) );
	}

	/**
	 * @covers IP::isValid
	 */
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
		foreach ( range( 0x0, 0xFFFF, 0xF ) as $i ) {
			$a = sprintf( "%04x", $i );
			$b = sprintf( "%03x", $i );
			$c = sprintf( "%02x", $i );
			foreach ( array_unique( array( $a, $b, $c ) ) as $f ) {
				$ip = "$f:$f:$f:$f:$f:$f:$f:$f";
				$this->assertTrue( IP::isValid( $ip ) , "$ip is a valid IPv6 address" );
			}
		}
		// test with some abbreviations
		$this->assertFalse( IP::isValid( ':fc:100::' ), 'IPv6 starting with lone ":"' );
		$this->assertFalse( IP::isValid( 'fc:100:::' ), 'IPv6 ending with a ":::"' );
		$this->assertFalse( IP::isValid( 'fc:300' ), 'IPv6 with only 2 words' );
		$this->assertFalse( IP::isValid( 'fc:100:300' ), 'IPv6 with only 3 words' );

		$this->assertTrue( IP::isValid( 'fc:100::' ) );
		$this->assertTrue( IP::isValid( 'fc:100:a:d:1:e::' ) );
		$this->assertTrue( IP::isValid( 'fc:100:a:d:1:e:ac::' ) );

		$this->assertTrue( IP::isValid( 'fc::100' ), 'IPv6 with "::" and 2 words' );
		$this->assertTrue( IP::isValid( 'fc::100:a' ), 'IPv6 with "::" and 3 words' );
		$this->assertTrue( IP::isValid( '2001::df'), 'IPv6 with "::" and 2 words' );
		$this->assertTrue( IP::isValid( '2001:5c0:1400:a::df'), 'IPv6 with "::" and 5 words' );
		$this->assertTrue( IP::isValid( '2001:5c0:1400:a::df:2'), 'IPv6 with "::" and 6 words' );
		$this->assertTrue( IP::isValid( 'fc::100:a:d:1' ), 'IPv6 with "::" and 5 words' );
		$this->assertTrue( IP::isValid( 'fc::100:a:d:1:e:ac' ), 'IPv6 with "::" and 7 words' );

		$this->assertFalse( IP::isValid( 'fc:100:a:d:1:e:ac:0::' ), 'IPv6 with 8 words ending with "::"' );
		$this->assertFalse( IP::isValid( 'fc:100:a:d:1:e:ac:0:1::' ), 'IPv6 with 9 words ending with "::"' );
	}

	/**
	 * @covers IP::isValid
	 */
	public function testInvalidIPs() {
		// Out of range...
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
		// Have CIDR
		$ipCIDRs = array(
			'212.35.31.121/32',
			'212.35.31.121/18',
			'212.35.31.121/24',
			'::ff:d:321:5/96',
			'ff::d3:321:5/116',
			'c:ff:12:1:ea:d:321:5/120',
		);
		foreach ( $ipCIDRs as $i ) {
			$this->assertFalse( IP::isValid( $i ),
				"$i is an invalid IP address because it is a block" );
		}
		// Incomplete/garbage
		$invalid = array(
			'www.xn--var-xla.net',
			'216.17.184.G',
			'216.17.184.1.',
			'216.17.184',
			'216.17.184.',
			'256.17.184.1'
		);
		foreach ( $invalid as $i ) {
			$this->assertFalse( IP::isValid( $i ), "$i is an invalid IP address" );
		}
	}

	/**
	 * @covers IP::isValidBlock
	 */
	public function testValidBlocks() {
		$valid = array(
			'116.17.184.5/32',
			'0.17.184.5/30',
			'16.17.184.1/24',
			'30.242.52.14/1',
			'10.232.52.13/8',
			'30.242.52.14/0',
			'::e:f:2001/96',
			'::c:f:2001/128',
			'::10:f:2001/70',
			'::fe:f:2001/1',
			'::6d:f:2001/8',
			'::fe:f:2001/0',
		);
		foreach ( $valid as $i ) {
			$this->assertTrue( IP::isValidBlock( $i ), "$i is a valid IP block" );
		}
	}

	/**
	 * @covers IP::isValidBlock
	 */
	public function testInvalidBlocks() {
		$invalid = array(
			'116.17.184.5/33',
			'0.17.184.5/130',
			'16.17.184.1/-1',
			'10.232.52.13/*',
			'7.232.52.13/ab',
			'11.232.52.13/',
			'::e:f:2001/129',
			'::c:f:2001/228',
			'::10:f:2001/-1',
			'::6d:f:2001/*',
			'::86:f:2001/ab',
			'::23:f:2001/',
		);
		foreach ( $invalid as $i ) {
			$this->assertFalse( IP::isValidBlock( $i ), "$i is not a valid IP block" );
		}
	}

	/**
	 * Improve IP::sanitizeIP() code coverage
	 * @todo Most probably incomplete
	 */
	public function testSanitizeIP() {
		$this->assertNull( IP::sanitizeIP('')  );
		$this->assertNull( IP::sanitizeIP(' ') );
	}

	/**
	 * test wrapper around ip2long which might return -1 or false depending on PHP version
	 * @covers IP::toUnsigned
	 */
	public function testip2longWrapper() {
		// @todo FIXME: Add more tests ?
		$this->assertEquals( pow(2,32) - 1, IP::toUnsigned( '255.255.255.255' ));
		$i = 'IN.VA.LI.D';
		$this->assertFalse( IP::toUnSigned( $i ) );
	}

	/**
	 * @covers IP::isPublic
	 */
	public function testPrivateIPs() {
		$private = array( 'fc00::3', 'fc00::ff', '::1', '10.0.0.1', '172.16.0.1', '192.168.0.1' );
		foreach ( $private as $p ) {
			$this->assertFalse( IP::isPublic( $p ), "$p is not a public IP address" );
		}
		$public = array( '2001:5c0:1000:a::133', 'fc::3', '00FC::' );
		foreach ( $public as $p ) {
			$this->assertTrue( IP::isPublic( $p ), "$p is a public IP address" );
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

	/**
	 * @covers IP::hexToQuad
	 */
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

	/**
	 * @covers IP::hexToOctet
	 */
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

	/**
	 * IP::parseCIDR() returns an array containing a signed IP address
	 * representing the network mask and the bit mask.
	 * @covers IP::parseCIDR
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

		// @todo FIXME: Add more tests.

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


	/**
	 * @covers IP::canonicalize
	 */
	public function testIPCanonicalizeOnValidIp() {
		$this->assertEquals( '192.0.2.152', IP::canonicalize( '192.0.2.152' ),
	   	'Canonicalization of a valid IP returns it unchanged' );
	}

	/**
	 * @covers IP::canonicalize
	 */
	public function testIPCanonicalizeMappedAddress() {
		$this->assertEquals(
			'192.0.2.152',
			IP::canonicalize( '::ffff:192.0.2.152' )
		);
		$this->assertEquals(
			'192.0.2.152',
			IP::canonicalize( '::192.0.2.152' )
		);
	}

	/**
	 * Issues there are most probably from IP::toHex() or IP::parseRange()
	 * @covers IP::isInRange
	 * @dataProvider provideIPsAndRanges
	 */
	public function testIPIsInRange( $expected, $addr, $range, $message = '' ) {
		$this->assertEquals(
			$expected,
			IP::isInRange( $addr, $range ),
			$message
		);
	}

	/** Provider for testIPIsInRange() */
	function provideIPsAndRanges() {
			# Format: (expected boolean, address, range, optional message)
		return array(
			# IPv4
			array( true , '192.0.2.0'   , '192.0.2.0/24', 'Network address' ),
			array( true , '192.0.2.77'  , '192.0.2.0/24', 'Simple address' ),
			array( true , '192.0.2.255' , '192.0.2.0/24', 'Broadcast address' ),

			array( false, '0.0.0.0'     , '192.0.2.0/24' ),
			array( false, '255.255.255' , '192.0.2.0/24' ),

			# IPv6
			array( false, '::1'    , '2001:DB8::/32' ),
			array( false, '::'     , '2001:DB8::/32' ),
			array( false, 'FE80::1', '2001:DB8::/32' ),

			array( true , '2001:DB8::'  , '2001:DB8::/32' ),
			array( true , '2001:0DB8::' , '2001:DB8::/32' ),
			array( true , '2001:DB8::1' , '2001:DB8::/32' ),
			array( true , '2001:0DB8::1', '2001:DB8::/32' ),
			array( true , '2001:0DB8:FFFF:FFFF:FFFF:FFFF:FFFF:FFFF',
				'2001:DB8::/32' ),

			array( false, '2001:0DB8:F::', '2001:DB8::/96' ),
		);
	}

	/**
	 * Test for IP::splitHostAndPort().
	 * @dataProvider provideSplitHostAndPort
	 */
	function testSplitHostAndPort( $expected, $input, $description ) {
		$this->assertEquals( $expected, IP::splitHostAndPort( $input ), $description );
	}

	/**
	 * Provider for IP::splitHostAndPort()
	 */
	function provideSplitHostAndPort() {
		return array(
			array( false, '[', 'Unclosed square bracket' ),
			array( false, '[::', 'Unclosed square bracket 2' ),
			array( array( '::', false ), '::', 'Bare IPv6 0' ),
			array( array( '::1', false ), '::1', 'Bare IPv6 1' ),
			array( array( '::', false ), '[::]', 'Bracketed IPv6 0' ),
			array( array( '::1', false ), '[::1]', 'Bracketed IPv6 1' ),
			array( array( '::1', 80 ), '[::1]:80', 'Bracketed IPv6 with port' ),
			array( false, '::x', 'Double colon but no IPv6' ),
			array( array( 'x', 80 ), 'x:80', 'Hostname and port' ),
			array( false, 'x:x', 'Hostname and invalid port' ),
			array( array( 'x', false ), 'x', 'Plain hostname' )
		);
	}

	/**
	 * Test for IP::combineHostAndPort()
	 * @dataProvider provideCombineHostAndPort
	 */
	function testCombineHostAndPort( $expected, $input, $description ) {
		list( $host, $port, $defaultPort ) = $input;
		$this->assertEquals(
			$expected,
			IP::combineHostAndPort( $host, $port, $defaultPort ),
			$description );
	}

	/**
	 * Provider for IP::combineHostAndPort()
	 */
	function provideCombineHostAndPort() {
		return array(
			array( '[::1]', array( '::1', 2, 2 ), 'IPv6 default port' ),
			array( '[::1]:2', array( '::1', 2, 3 ), 'IPv6 non-default port' ),
			array( 'x', array( 'x', 2, 2 ), 'Normal default port' ),
			array( 'x:2', array( 'x', 2, 3 ), 'Normal non-default port' ),
		);
	}

	/**
	 * Test for IP::sanitizeRange()
	 * @dataProvider provideIPCIDRs
	 */
	function testSanitizeRange( $input, $expected, $description ) {
		$this->assertEquals( $expected, IP::sanitizeRange( $input ), $description );
	}

	/**
	 * Provider for IP::testSanitizeRange()
	 */
	function provideIPCIDRs() {
		return array(
			array( '35.56.31.252/16', '35.56.0.0/16', 'IPv4 range' ),
			array( '135.16.21.252/24', '135.16.21.0/24', 'IPv4 range' ),
			array( '5.36.71.252/32', '5.36.71.252/32', 'IPv4 silly range' ),
			array( '5.36.71.252', '5.36.71.252', 'IPv4 non-range' ),
			array( '0:1:2:3:4:c5:f6:7/96', '0:1:2:3:4:C5:0:0/96', 'IPv6 range' ),
			array( '0:1:2:3:4:5:6:7/120', '0:1:2:3:4:5:6:0/120', 'IPv6 range' ),
			array( '0:e1:2:3:4:5:e6:7/128', '0:E1:2:3:4:5:E6:7/128', 'IPv6 silly range' ),
			array( '0:c1:A2:3:4:5:c6:7', '0:C1:A2:3:4:5:C6:7', 'IPv6 non range' ),
		);
	}

	/**
	 * Test for IP::prettifyIP()
	 * @dataProvider provideIPsToPrettify
	 */
	function testPrettifyIP( $ip, $prettified ) {
		$this->assertEquals( $prettified, IP::prettifyIP( $ip ), "Prettify of $ip" );
	}

	/**
	 * Provider for IP::testPrettifyIP()
	 */
	function provideIPsToPrettify() {
		return array(
			array( '0:0:0:0:0:0:0:0', '::' ),
			array( '0:0:0::0:0:0', '::' ),
			array( '0:0:0:1:0:0:0:0', '0:0:0:1::' ),
			array( '0:0::f', '::f' ),
			array( '0::0:0:0:33:fef:b', '::33:fef:b' ),
			array( '3f:535:0:0:0:0:e:fbb', '3f:535::e:fbb' ),
			array( '0:0:fef:0:0:0:e:fbb', '0:0:fef::e:fbb' ),
			array( 'abbc:2004::0:0:0:0', 'abbc:2004::' ),
			array( 'cebc:2004:f:0:0:0:0:0', 'cebc:2004:f::' ),
			array( '0:0:0:0:0:0:0:0/16', '::/16' ),
			array( '0:0:0::0:0:0/64', '::/64' ),
			array( '0:0::f/52', '::f/52' ),
			array( '::0:0:33:fef:b/52', '::33:fef:b/52' ),
			array( '3f:535:0:0:0:0:e:fbb/48', '3f:535::e:fbb/48' ),
			array( '0:0:fef:0:0:0:e:fbb/96', '0:0:fef::e:fbb/96' ),
			array( 'abbc:2004:0:0::0:0/40', 'abbc:2004::/40' ),
			array( 'aebc:2004:f:0:0:0:0:0/80', 'aebc:2004:f::/80' ),
		);
	}
}
