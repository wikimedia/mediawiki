<?php
/* 
 * Tests for IP validity functions. Ported from /t/inc/IP.t by avar.
 */

class IPTest extends PHPUnit_Framework_TestCase {
	// not sure it should be tested with boolean false. hashar 20100924 
	public function testisIPAddress() {
		$this->assertFalse( IP::isIPAddress( false ) );
		$this->assertFalse( IP::isIPAddress( '2001:0DB8::A:1::1'), 'IPv6 with a double :: occurence' );
		$this->assertFalse( IP::isIPAddress( '2001:0DB8::A:1::'), 'IPv6 with a double :: occurence, last at end' );
		$this->assertFalse( IP::isIPAddress( '::2001:0DB8::5:1'), 'IPv6 with a double :: occurence, firt at beginning' );
	}

	/**
	 * @expectedException MWException
	 */
	public function testArrayIsNotIPAddress() {
		IP::isIPAddress( array('') );
	}
	/**
	 * @expectedException MWException
	 */
	public function testArrayIsNotIPv6() {
		IP::isIPv6( array('') );
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
		$this->assertEquals( -1           , IP::toSigned( '255.255.255.255' )) ;
		$i = 'IN.VA.LI.D';
		$this->assertFalse( IP::toUnSigned( $i ) );
		$this->assertFalse( IP::toSigned(   $i ) );
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
		$this->assertEquals( '0.0.0.0'        , IP::hexToQuad( '0' ) );
		$this->assertEquals( '0.0.0.1'        , IP::hexToQuad( '00000001' ) );
		$this->assertEquals( '255.0.0.0'      , IP::hexToQuad( 'FF000000' ) );
		$this->assertEquals( '255.255.255.255', IP::hexToQuad( 'FFFFFFFF' ) );
		$this->assertEquals( '10.188.222.255' , IP::hexToQuad( '0ABCDEFF' ) );
		
		$this->assertNotEquals( '0.0.0.1'        , IP::hexToQuad( '1' ) );
		$this->assertNotEquals( '0.0.0.255'      , IP::hexToQuad( 'FF' ) );
		$this->assertNotEquals( '0.0.255.0'      , IP::hexToQuad( 'FF00' ) );
	}

	/*
	 * IP::parseCIDR() returns an array containing a signed IP address
	 * representing the network mask and the bit mask.
	 */
	function testCIDRParsing() {
		$this->assertFalseCIDR( '192.0.2.0' , "missing mask"    );	
		$this->assertFalseCIDR( '192.0.2.0/', "missing bitmask" );
		
		// code calls IP::toSigned()

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
