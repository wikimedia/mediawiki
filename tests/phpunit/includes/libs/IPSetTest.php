<?php

/**
 * @group IPSet
 */
class IPSetTest extends MediaWikiTestCase {
	private $oldNesting;

	public function setUp() {
		parent::setUp();

		/**
		 * @fixme
		 * Our tree-optimizer/compressor functions can recurse up to ~128 levels
		 * in the worst case on certain IPv6 inputs, but xdebug defaults to
		 * a limit of 100, and that's *including* all the outer-scope functions
		 * before we enter our local recursion here.
		 */
		$oldNesting = ini_get( 'xdebug.max_nesting_level' );
		if ( $oldNesting > 0 && $oldNesting < 200 ) {
			$this->oldNesting = $oldNesting;
			ini_set( 'xdebug.max_nesting_level', 200 );
		}
	}

	public function tearDown() {
		if ( $this->oldNesting ) {
			ini_set( 'xdebug.max_nesting_level', $this->oldNesting );
		}
		parent::tearDown();
	}

	/**
	 * @covers IPSet
	 */
	public function testIPSet() {
		// a subset of the long wmf prod explicit list
		$cfg1 = array(
			'208.80.152.162',
			'10.64.0.123', '10.64.0.124', '10.64.0.125', '10.64.0.126',
			'10.64.0.127', '10.64.0.128', '10.64.0.129',
			'10.64.32.104', '10.64.32.105', '10.64.32.106', '10.64.32.107',
			'91.198.174.45', '91.198.174.46', '91.198.174.47',
			'91.198.174.57',
			'2620:0:862:1:A6BA:DBFF:FE30:CFB3',
			'91.198.174.58',
			'2620:0:862:1:A6BA:DBFF:FE38:FFDA',
			'208.80.152.16', '208.80.152.17', '208.80.152.18', '208.80.152.19',
			'91.198.174.102', '91.198.174.103', '91.198.174.104', '91.198.174.105',
			'91.198.174.106', '91.198.174.107',
			'91.198.174.81',
			'2620:0:862:1:26B6:FDFF:FEF5:B2D4',
			'91.198.174.82',
			'2620:0:862:1:26B6:FDFF:FEF5:ABB4',
			'10.20.0.113',
			'2620:0:862:102:26B6:FDFF:FEF5:AD9C',
			'10.20.0.114',
			'2620:0:862:102:26B6:FDFF:FEF5:7C38',
		);

		$set1 = new IPSet( $cfg1 );
		$tests1 = array(
			'0.0.0.0' => false,
			'255.255.255.255' => false,
			'10.64.0.122' => false,
			'10.64.0.123' => true,
			'10.64.0.124' => true,
			'10.64.0.129' => true,
			'10.64.0.130' => false,
			'91.198.174.81' => true,
			'91.198.174.80' => false,
			'0::0' => false,
			'ffff:ffff:ffff:ffff:FFFF:FFFF:FFFF:FFFF' => false,
			'2001:db8::1234' => false,
			'2620:0:862:1:26b6:fdff:fef5:abb3' => false,
			'2620:0:862:1:26b6:fdff:fef5:abb4' => true,
			'2620:0:862:1:26b6:fdff:fef5:abb5' => false,
		);

		foreach ( $tests1 as $ip => $expected ) {
			$result = $set1->match( $ip );
			$this->assertEquals( $expected, $result, "Correct result in set1 for $ip" );
		}

		// a recent proposed dataset for using subnets instead:
		$cfg2 = array(
			'208.80.154.0/26',
			'2620:0:861:1::/64',
			'208.80.154.128/26',
			'2620:0:861:2::/64',
			'208.80.154.64/26',
			'2620:0:861:3::/64',
			'208.80.155.96/27',
			'2620:0:861:4::/64',
			'10.64.0.0/22',
			'2620:0:861:101::/64',
			'10.64.16.0/22',
			'2620:0:861:102::/64',
			'10.64.32.0/22',
			'2620:0:861:103::/64',
			'10.64.48.0/22',
			'2620:0:861:107::/64',
			'91.198.174.0/25',
			'2620:0:862:1::/64',
			'10.20.0.0/24',
			'2620:0:862:102::/64',
			'10.128.0.0/24',
			'2620:0:863:101::/64',
			'10.2.4.26',
		);

		$set2 = new IPSet( $cfg2 );
		$tests2 = array(
			'0.0.0.0' => false,
			'255.255.255.255' => false,
			'10.2.4.25' => false,
			'10.2.4.26' => true,
			'10.2.4.27' => false,
			'10.20.0.255' => true,
			'10.128.0.0' => true,
			'10.64.17.55' => true,
			'10.64.20.0' => false,
			'10.64.27.207' => false,
			'10.64.31.255' => false,
			'0::0' => false,
			'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff' => false,
			'2001:DB8::1' => false,
			'2620:0:861:106::45' => false,
			'2620:0:862:103::' => false,
			'2620:0:862:102:10:20:0:113' => true,
		);

		foreach ( $tests2 as $ip => $expected ) {
			$result = $set2->match( $ip );
			$this->assertEquals( $expected, $result, "Correct result in set2 for $ip" );
		}
	}
}
