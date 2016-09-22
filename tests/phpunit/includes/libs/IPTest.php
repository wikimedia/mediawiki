<?php
/**
 * Tests for IP validity functions.
 *
 * Ported from /t/inc/IP.t by avar.
 *
 * @group IP
 * @todo Test methods in this call should be split into a method and a
 * dataprovider.
 */

class IPTest extends PHPUnit_Framework_TestCase {
	/**
	 * @covers IP::isIPAddress
	 * @dataProvider provideInvalidIPs
	 */
	public function isNotIPAddress( $val, $desc ) {
		$this->assertFalse( IP::isIPAddress( $val ), $desc );
	}

	/**
	 * Provide a list of things that aren't IP addresses
	 */
	public function provideInvalidIPs() {
		return [
			[ false, 'Boolean false is not an IP' ],
			[ true, 'Boolean true is not an IP' ],
			[ '', 'Empty string is not an IP' ],
			[ 'abc', 'Garbage IP string' ],
			[ ':', 'Single ":" is not an IP' ],
			[ '2001:0DB8::A:1::1', 'IPv6 with a double :: occurrence' ],
			[ '2001:0DB8::A:1::', 'IPv6 with a double :: occurrence, last at end' ],
			[ '::2001:0DB8::5:1', 'IPv6 with a double :: occurrence, firt at beginning' ],
			[ '124.24.52', 'IPv4 not enough quads' ],
			[ '24.324.52.13', 'IPv4 out of range' ],
			[ '.24.52.13', 'IPv4 starts with period' ],
			[ 'fc:100:300', 'IPv6 with only 3 words' ],
		];
	}

	/**
	 * @covers IP::isIPAddress
	 */
	public function testisIPAddress() {
		$this->assertTrue( IP::isIPAddress( '::' ), 'RFC 4291 IPv6 Unspecified Address' );
		$this->assertTrue( IP::isIPAddress( '::1' ), 'RFC 4291 IPv6 Loopback Address' );
		$this->assertTrue( IP::isIPAddress( '74.24.52.13/20' ), 'IPv4 range' );
		$this->assertTrue( IP::isIPAddress( 'fc:100:a:d:1:e:ac:0/24' ), 'IPv6 range' );
		$this->assertTrue( IP::isIPAddress( 'fc::100:a:d:1:e:ac/96' ), 'IPv6 range with "::"' );

		$validIPs = [ 'fc:100::', 'fc:100:a:d:1:e:ac::', 'fc::100', '::fc:100:a:d:1:e:ac',
			'::fc', 'fc::100:a:d:1:e:ac', 'fc:100:a:d:1:e:ac:0', '124.24.52.13', '1.24.52.13' ];
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
		$this->assertFalse(
			IP::isIPv6( 'fc:100:a:d:1:e:ac:0:1::' ),
			'IPv6 with 9 words ending with "::"'
		);

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
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d' ), 'IPv6 with "::" and 4 words' );
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d:1' ), 'IPv6 with "::" and 5 words' );
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d:1:e' ), 'IPv6 with "::" and 6 words' );
		$this->assertTrue( IP::isIPv6( 'fc::100:a:d:1:e:ac' ), 'IPv6 with "::" and 7 words' );
		$this->assertTrue( IP::isIPv6( '2001::df' ), 'IPv6 with "::" and 2 words' );
		$this->assertTrue( IP::isIPv6( '2001:5c0:1400:a::df' ), 'IPv6 with "::" and 5 words' );
		$this->assertTrue( IP::isIPv6( '2001:5c0:1400:a::df:2' ), 'IPv6 with "::" and 6 words' );

		$this->assertFalse( IP::isIPv6( 'fc::100:a:d:1:e:ac:0' ), 'IPv6 with "::" and 8 words' );
		$this->assertFalse( IP::isIPv6( 'fc::100:a:d:1:e:ac:0:1' ), 'IPv6 with 9 words' );

		$this->assertTrue( IP::isIPv6( 'fc:100:a:d:1:e:ac:0' ) );
	}

	/**
	 * @covers IP::isIPv4
	 * @dataProvider provideInvalidIPv4Addresses
	 */
	public function testisNotIPv4( $bogusIP, $desc ) {
		$this->assertFalse( IP::isIPv4( $bogusIP ), $desc );
	}

	public function provideInvalidIPv4Addresses() {
		return [
			[ false, 'Boolean false is not an IP' ],
			[ true, 'Boolean true is not an IP' ],
			[ '', 'Empty string is not an IP' ],
			[ 'abc', 'Letters are not an IP' ],
			[ ':', 'A colon is not an IP' ],
			[ '124.24.52', 'IPv4 not enough quads' ],
			[ '24.324.52.13', 'IPv4 out of range' ],
			[ '.24.52.13', 'IPv4 starts with period' ],
		];
	}

	/**
	 * @covers IP::isIPv4
	 * @dataProvider provideValidIPv4Address
	 */
	public function testIsIPv4( $ip, $desc ) {
		$this->assertTrue( IP::isIPv4( $ip ), $desc );
	}

	/**
	 * Provide some IPv4 addresses and ranges
	 */
	public function provideValidIPv4Address() {
		return [
			[ '124.24.52.13', 'Valid IPv4 address' ],
			[ '1.24.52.13', 'Another valid IPv4 address' ],
			[ '74.24.52.13/20', 'An IPv4 range' ],
		];
	}

	/**
	 * @covers IP::isValid
	 */
	public function testValidIPs() {
		foreach ( range( 0, 255 ) as $i ) {
			$a = sprintf( "%03d", $i );
			$b = sprintf( "%02d", $i );
			$c = sprintf( "%01d", $i );
			foreach ( array_unique( [ $a, $b, $c ] ) as $f ) {
				$ip = "$f.$f.$f.$f";
				$this->assertTrue( IP::isValid( $ip ), "$ip is a valid IPv4 address" );
			}
		}
		foreach ( range( 0x0, 0xFFFF, 0xF ) as $i ) {
			$a = sprintf( "%04x", $i );
			$b = sprintf( "%03x", $i );
			$c = sprintf( "%02x", $i );
			foreach ( array_unique( [ $a, $b, $c ] ) as $f ) {
				$ip = "$f:$f:$f:$f:$f:$f:$f:$f";
				$this->assertTrue( IP::isValid( $ip ), "$ip is a valid IPv6 address" );
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
		$this->assertTrue( IP::isValid( '2001::df' ), 'IPv6 with "::" and 2 words' );
		$this->assertTrue( IP::isValid( '2001:5c0:1400:a::df' ), 'IPv6 with "::" and 5 words' );
		$this->assertTrue( IP::isValid( '2001:5c0:1400:a::df:2' ), 'IPv6 with "::" and 6 words' );
		$this->assertTrue( IP::isValid( 'fc::100:a:d:1' ), 'IPv6 with "::" and 5 words' );
		$this->assertTrue( IP::isValid( 'fc::100:a:d:1:e:ac' ), 'IPv6 with "::" and 7 words' );

		$this->assertFalse(
			IP::isValid( 'fc:100:a:d:1:e:ac:0::' ),
			'IPv6 with 8 words ending with "::"'
		);
		$this->assertFalse(
			IP::isValid( 'fc:100:a:d:1:e:ac:0:1::' ),
			'IPv6 with 9 words ending with "::"'
		);
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
			foreach ( array_unique( [ $a, $b, $c ] ) as $f ) {
				$ip = "$f.$f.$f.$f";
				$this->assertFalse( IP::isValid( $ip ), "$ip is not a valid IPv4 address" );
			}
		}
		foreach ( range( 'g', 'z' ) as $i ) {
			$a = sprintf( "%04s", $i );
			$b = sprintf( "%03s", $i );
			$c = sprintf( "%02s", $i );
			foreach ( array_unique( [ $a, $b, $c ] ) as $f ) {
				$ip = "$f:$f:$f:$f:$f:$f:$f:$f";
				$this->assertFalse( IP::isValid( $ip ), "$ip is not a valid IPv6 address" );
			}
		}
		// Have CIDR
		$ipCIDRs = [
			'212.35.31.121/32',
			'212.35.31.121/18',
			'212.35.31.121/24',
			'::ff:d:321:5/96',
			'ff::d3:321:5/116',
			'c:ff:12:1:ea:d:321:5/120',
		];
		foreach ( $ipCIDRs as $i ) {
			$this->assertFalse( IP::isValid( $i ),
				"$i is an invalid IP address because it is a block" );
		}
		// Incomplete/garbage
		$invalid = [
			'www.xn--var-xla.net',
			'216.17.184.G',
			'216.17.184.1.',
			'216.17.184',
			'216.17.184.',
			'256.17.184.1'
		];
		foreach ( $invalid as $i ) {
			$this->assertFalse( IP::isValid( $i ), "$i is an invalid IP address" );
		}
	}

	/**
	 * Provide some valid IP blocks
	 */
	public function provideValidBlocks() {
		return [
			[ '116.17.184.5/32' ],
			[ '0.17.184.5/30' ],
			[ '16.17.184.1/24' ],
			[ '30.242.52.14/1' ],
			[ '10.232.52.13/8' ],
			[ '30.242.52.14/0' ],
			[ '::e:f:2001/96' ],
			[ '::c:f:2001/128' ],
			[ '::10:f:2001/70' ],
			[ '::fe:f:2001/1' ],
			[ '::6d:f:2001/8' ],
			[ '::fe:f:2001/0' ],
		];
	}

	/**
	 * @covers IP::isValidBlock
	 * @dataProvider provideValidBlocks
	 */
	public function testValidBlocks( $block ) {
		$this->assertTrue( IP::isValidBlock( $block ), "$block is a valid IP block" );
	}

	/**
	 * @covers IP::isValidBlock
	 * @dataProvider provideInvalidBlocks
	 */
	public function testInvalidBlocks( $invalid ) {
		$this->assertFalse( IP::isValidBlock( $invalid ), "$invalid is not a valid IP block" );
	}

	public function provideInvalidBlocks() {
		return [
			[ '116.17.184.5/33' ],
			[ '0.17.184.5/130' ],
			[ '16.17.184.1/-1' ],
			[ '10.232.52.13/*' ],
			[ '7.232.52.13/ab' ],
			[ '11.232.52.13/' ],
			[ '::e:f:2001/129' ],
			[ '::c:f:2001/228' ],
			[ '::10:f:2001/-1' ],
			[ '::6d:f:2001/*' ],
			[ '::86:f:2001/ab' ],
			[ '::23:f:2001/' ],
		];
	}

	/**
	 * @covers IP::sanitizeIP
	 * @dataProvider provideSanitizeIP
	 */
	public function testSanitizeIP( $expected, $input ) {
		$result = IP::sanitizeIP( $input );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Provider for IP::testSanitizeIP()
	 */
	public static function provideSanitizeIP() {
		return [
			[ '0.0.0.0', '0.0.0.0' ],
			[ '0.0.0.0', '00.00.00.00' ],
			[ '0.0.0.0', '000.000.000.000' ],
			[ '141.0.11.253', '141.000.011.253' ],
			[ '1.2.4.5', '1.2.4.5' ],
			[ '1.2.4.5', '01.02.04.05' ],
			[ '1.2.4.5', '001.002.004.005' ],
			[ '10.0.0.1', '010.0.000.1' ],
			[ '80.72.250.4', '080.072.250.04' ],
			[ 'Foo.1000.00', 'Foo.1000.00' ],
			[ 'Bar.01', 'Bar.01' ],
			[ 'Bar.010', 'Bar.010' ],
			[ null, '' ],
			[ null, ' ' ]
		];
	}

	/**
	 * @covers IP::toHex
	 * @dataProvider provideToHex
	 */
	public function testToHex( $expected, $input ) {
		$result = IP::toHex( $input );
		$this->assertTrue( $result === false || is_string( $result ) );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Provider for IP::testToHex()
	 */
	public static function provideToHex() {
		return [
			[ '00000001', '0.0.0.1' ],
			[ '01020304', '1.2.3.4' ],
			[ '7F000001', '127.0.0.1' ],
			[ '80000000', '128.0.0.0' ],
			[ 'DEADCAFE', '222.173.202.254' ],
			[ 'FFFFFFFF', '255.255.255.255' ],
			[ '8D000BFD', '141.000.11.253' ],
			[ false, 'IN.VA.LI.D' ],
			[ 'v6-00000000000000000000000000000001', '::1' ],
			[ 'v6-20010DB885A3000000008A2E03707334', '2001:0db8:85a3:0000:0000:8a2e:0370:7334' ],
			[ 'v6-20010DB885A3000000008A2E03707334', '2001:db8:85a3::8a2e:0370:7334' ],
			[ false, 'IN:VA::LI:D' ],
			[ false, ':::1' ]
		];
	}

	/**
	 * @covers IP::isPublic
	 * @dataProvider provideIsPublic
	 */
	public function testIsPublic( $expected, $input ) {
		$result = IP::isPublic( $input );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Provider for IP::testIsPublic()
	 */
	public static function provideIsPublic() {
		return [
			[ false, 'fc00::3' ], # RFC 4193 (local)
			[ false, 'fc00::ff' ], # RFC 4193 (local)
			[ false, '127.1.2.3' ], # loopback
			[ false, '::1' ], # loopback
			[ false, 'fe80::1' ], # link-local
			[ false, '169.254.1.1' ], # link-local
			[ false, '10.0.0.1' ], # RFC 1918 (private)
			[ false, '172.16.0.1' ], # RFC 1918 (private)
			[ false, '192.168.0.1' ], # RFC 1918 (private)
			[ true, '2001:5c0:1000:a::133' ], # public
			[ true, 'fc::3' ], # public
			[ true, '00FC::' ] # public
		];
	}

	// Private wrapper used to test CIDR Parsing.
	private function assertFalseCIDR( $CIDR, $msg = '' ) {
		$ff = [ false, false ];
		$this->assertEquals( $ff, IP::parseCIDR( $CIDR ), $msg );
	}

	// Private wrapper to test network shifting using only dot notation
	private function assertNet( $expected, $CIDR ) {
		$parse = IP::parseCIDR( $CIDR );
		$this->assertEquals( $expected, long2ip( $parse[0] ), "network shifting $CIDR" );
	}

	/**
	 * @covers IP::hexToQuad
	 * @dataProvider provideIPsAndHexes
	 */
	public function testHexToQuad( $ip, $hex ) {
		$this->assertEquals( $ip, IP::hexToQuad( $hex ) );
	}

	/**
	 * Provide some IP addresses and their equivalent hex representations
	 */
	public function provideIPsandHexes() {
		return [
			[ '0.0.0.1', '00000001' ],
			[ '255.0.0.0', 'FF000000' ],
			[ '255.255.255.255', 'FFFFFFFF' ],
			[ '10.188.222.255', '0ABCDEFF' ],
			// hex not left-padded...
			[ '0.0.0.0', '0' ],
			[ '0.0.0.1', '1' ],
			[ '0.0.0.255', 'FF' ],
			[ '0.0.255.0', 'FF00' ],
		];
	}

	/**
	 * @covers IP::hexToOctet
	 * @dataProvider provideOctetsAndHexes
	 */
	public function testHexToOctet( $octet, $hex ) {
		$this->assertEquals( $octet, IP::hexToOctet( $hex ) );
	}

	/**
	 * Provide some hex and octet representations of the same IPs
	 */
	public function provideOctetsAndHexes() {
		return [
			[ '0:0:0:0:0:0:0:1', '00000000000000000000000000000001' ],
			[ '0:0:0:0:0:0:FF:3', '00000000000000000000000000FF0003' ],
			[ '0:0:0:0:0:0:FF00:6', '000000000000000000000000FF000006' ],
			[ '0:0:0:0:0:0:FCCF:FAFF', '000000000000000000000000FCCFFAFF' ],
			[ 'FFFF:FFFF:FFFF:FFFF:FFFF:FFFF:FFFF:FFFF', 'FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF' ],
			// hex not left-padded...
			[ '0:0:0:0:0:0:0:0', '0' ],
			[ '0:0:0:0:0:0:0:1', '1' ],
			[ '0:0:0:0:0:0:0:FF', 'FF' ],
			[ '0:0:0:0:0:0:0:FFD0', 'FFD0' ],
			[ '0:0:0:0:0:0:FA00:0', 'FA000000' ],
			[ '0:0:0:0:0:0:FCCF:FAFF', 'FCCFFAFF' ],
		];
	}

	/**
	 * IP::parseCIDR() returns an array containing a signed IP address
	 * representing the network mask and the bit mask.
	 * @covers IP::parseCIDR
	 */
	public function testCIDRParsing() {
		$this->assertFalseCIDR( '192.0.2.0', "missing mask" );
		$this->assertFalseCIDR( '192.0.2.0/', "missing bitmask" );

		// Verify if statement
		$this->assertFalseCIDR( '256.0.0.0/32', "invalid net" );
		$this->assertFalseCIDR( '192.0.2.0/AA', "mask not numeric" );
		$this->assertFalseCIDR( '192.0.2.0/-1', "mask < 0" );
		$this->assertFalseCIDR( '192.0.2.0/33', "mask > 32" );

		// Check internal logic
		# 0 mask always result in array(0,0)
		$this->assertEquals( [ 0, 0 ], IP::parseCIDR( '192.0.0.2/0' ) );
		$this->assertEquals( [ 0, 0 ], IP::parseCIDR( '0.0.0.0/0' ) );
		$this->assertEquals( [ 0, 0 ], IP::parseCIDR( '255.255.255.255/0' ) );

		// @todo FIXME: Add more tests.

		# This part test network shifting
		$this->assertNet( '192.0.0.0', '192.0.0.2/24' );
		$this->assertNet( '192.168.5.0', '192.168.5.13/24' );
		$this->assertNet( '10.0.0.160', '10.0.0.161/28' );
		$this->assertNet( '10.0.0.0', '10.0.0.3/28' );
		$this->assertNet( '10.0.0.0', '10.0.0.3/30' );
		$this->assertNet( '10.0.0.4', '10.0.0.4/30' );
		$this->assertNet( '172.17.32.0', '172.17.35.48/21' );
		$this->assertNet( '10.128.0.0', '10.135.0.0/9' );
		$this->assertNet( '134.0.0.0', '134.0.5.1/8' );
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
	public static function provideIPsAndRanges() {
		# Format: (expected boolean, address, range, optional message)
		return [
			# IPv4
			[ true, '192.0.2.0', '192.0.2.0/24', 'Network address' ],
			[ true, '192.0.2.77', '192.0.2.0/24', 'Simple address' ],
			[ true, '192.0.2.255', '192.0.2.0/24', 'Broadcast address' ],

			[ false, '0.0.0.0', '192.0.2.0/24' ],
			[ false, '255.255.255', '192.0.2.0/24' ],

			# IPv6
			[ false, '::1', '2001:DB8::/32' ],
			[ false, '::', '2001:DB8::/32' ],
			[ false, 'FE80::1', '2001:DB8::/32' ],

			[ true, '2001:DB8::', '2001:DB8::/32' ],
			[ true, '2001:0DB8::', '2001:DB8::/32' ],
			[ true, '2001:DB8::1', '2001:DB8::/32' ],
			[ true, '2001:0DB8::1', '2001:DB8::/32' ],
			[ true, '2001:0DB8:FFFF:FFFF:FFFF:FFFF:FFFF:FFFF',
				'2001:DB8::/32' ],

			[ false, '2001:0DB8:F::', '2001:DB8::/96' ],
		];
	}

	/**
	 * Test for IP::splitHostAndPort().
	 * @dataProvider provideSplitHostAndPort
	 */
	public function testSplitHostAndPort( $expected, $input, $description ) {
		$this->assertEquals( $expected, IP::splitHostAndPort( $input ), $description );
	}

	/**
	 * Provider for IP::splitHostAndPort()
	 */
	public static function provideSplitHostAndPort() {
		return [
			[ false, '[', 'Unclosed square bracket' ],
			[ false, '[::', 'Unclosed square bracket 2' ],
			[ [ '::', false ], '::', 'Bare IPv6 0' ],
			[ [ '::1', false ], '::1', 'Bare IPv6 1' ],
			[ [ '::', false ], '[::]', 'Bracketed IPv6 0' ],
			[ [ '::1', false ], '[::1]', 'Bracketed IPv6 1' ],
			[ [ '::1', 80 ], '[::1]:80', 'Bracketed IPv6 with port' ],
			[ false, '::x', 'Double colon but no IPv6' ],
			[ [ 'x', 80 ], 'x:80', 'Hostname and port' ],
			[ false, 'x:x', 'Hostname and invalid port' ],
			[ [ 'x', false ], 'x', 'Plain hostname' ]
		];
	}

	/**
	 * Test for IP::combineHostAndPort()
	 * @dataProvider provideCombineHostAndPort
	 */
	public function testCombineHostAndPort( $expected, $input, $description ) {
		list( $host, $port, $defaultPort ) = $input;
		$this->assertEquals(
			$expected,
			IP::combineHostAndPort( $host, $port, $defaultPort ),
			$description );
	}

	/**
	 * Provider for IP::combineHostAndPort()
	 */
	public static function provideCombineHostAndPort() {
		return [
			[ '[::1]', [ '::1', 2, 2 ], 'IPv6 default port' ],
			[ '[::1]:2', [ '::1', 2, 3 ], 'IPv6 non-default port' ],
			[ 'x', [ 'x', 2, 2 ], 'Normal default port' ],
			[ 'x:2', [ 'x', 2, 3 ], 'Normal non-default port' ],
		];
	}

	/**
	 * Test for IP::sanitizeRange()
	 * @dataProvider provideIPCIDRs
	 */
	public function testSanitizeRange( $input, $expected, $description ) {
		$this->assertEquals( $expected, IP::sanitizeRange( $input ), $description );
	}

	/**
	 * Provider for IP::testSanitizeRange()
	 */
	public static function provideIPCIDRs() {
		return [
			[ '35.56.31.252/16', '35.56.0.0/16', 'IPv4 range' ],
			[ '135.16.21.252/24', '135.16.21.0/24', 'IPv4 range' ],
			[ '5.36.71.252/32', '5.36.71.252/32', 'IPv4 silly range' ],
			[ '5.36.71.252', '5.36.71.252', 'IPv4 non-range' ],
			[ '0:1:2:3:4:c5:f6:7/96', '0:1:2:3:4:C5:0:0/96', 'IPv6 range' ],
			[ '0:1:2:3:4:5:6:7/120', '0:1:2:3:4:5:6:0/120', 'IPv6 range' ],
			[ '0:e1:2:3:4:5:e6:7/128', '0:E1:2:3:4:5:E6:7/128', 'IPv6 silly range' ],
			[ '0:c1:A2:3:4:5:c6:7', '0:C1:A2:3:4:5:C6:7', 'IPv6 non range' ],
		];
	}

	/**
	 * Test for IP::prettifyIP()
	 * @dataProvider provideIPsToPrettify
	 */
	public function testPrettifyIP( $ip, $prettified ) {
		$this->assertEquals( $prettified, IP::prettifyIP( $ip ), "Prettify of $ip" );
	}

	/**
	 * Provider for IP::testPrettifyIP()
	 */
	public static function provideIPsToPrettify() {
		return [
			[ '0:0:0:0:0:0:0:0', '::' ],
			[ '0:0:0::0:0:0', '::' ],
			[ '0:0:0:1:0:0:0:0', '0:0:0:1::' ],
			[ '0:0::f', '::f' ],
			[ '0::0:0:0:33:fef:b', '::33:fef:b' ],
			[ '3f:535:0:0:0:0:e:fbb', '3f:535::e:fbb' ],
			[ '0:0:fef:0:0:0:e:fbb', '0:0:fef::e:fbb' ],
			[ 'abbc:2004::0:0:0:0', 'abbc:2004::' ],
			[ 'cebc:2004:f:0:0:0:0:0', 'cebc:2004:f::' ],
			[ '0:0:0:0:0:0:0:0/16', '::/16' ],
			[ '0:0:0::0:0:0/64', '::/64' ],
			[ '0:0::f/52', '::f/52' ],
			[ '::0:0:33:fef:b/52', '::33:fef:b/52' ],
			[ '3f:535:0:0:0:0:e:fbb/48', '3f:535::e:fbb/48' ],
			[ '0:0:fef:0:0:0:e:fbb/96', '0:0:fef::e:fbb/96' ],
			[ 'abbc:2004:0:0::0:0/40', 'abbc:2004::/40' ],
			[ 'aebc:2004:f:0:0:0:0:0/80', 'aebc:2004:f::/80' ],
		];
	}
}
