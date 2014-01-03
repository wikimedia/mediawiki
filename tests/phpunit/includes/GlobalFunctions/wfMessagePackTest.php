<?php
/**
 * PHP Unit tests for MessagePack
 * @covers ::wfMessagePack
 */
class wfMessagePackTest extends MediaWikiTestCase {

	/* @var array Array of test cases, keyed by type. Each type is an array of
	 * (value, expected encoding as hex string). The expected values were
	 * generated using <https://github.com/onlinecity/msgpack-php>, which
	 * includes a serialization function.
	 */
	public $data = array(
		'integer' => array(
			array(  0, '00' ),
			array(  1, '01' ),
			array(  5, '05' ),
			array(  -1, 'ff' ),
			array(  -2, 'fe' ),
			array(  35, '23' ),
			array(  -35, 'd0dd' ),
			array(  128, 'cc80' ),
			array(  -128, 'd080' ),
			array(  1000, 'cd03e8' ),
			array(  -1000, 'd1fc18' ),
			array(  100000, 'ce000186a0' ),
			array(  -100000, 'd2fffe7960' ),
			array(  10000000000, 'cf00000002540be400' ),
			array(  -10000000000, 'd3fffffffdabf41c00' ),
			array(  -223372036854775807, 'd3fce66c50e2840001' ),
			array(  -9223372036854775807, 'd38000000000000001' ),
		),
		'NULL' => array(
			array( null, 'c0' ),
		),
		'boolean' => array(
			array( true, 'c3' ),
			array( false, 'c2' ),
		),
		'double' => array(
			array(  0.1, 'cb3fb999999999999a' ),
			array(  1.1, 'cb3ff199999999999a' ),
			array(  123.456, 'cb405edd2f1a9fbe77' ),
		),
		'string' => array(
			array(  '', 'a0' ),
			array( 'foobar', 'a6666f6f626172' ),
			array(
				'Lorem ipsum dolor sit amet amet.',
				'da00204c6f72656d20697073756d20646f6c6f722073697420616d657420616d65742e'
			),
		),
		'array' => array(
			array( array( 'abc', 'def', 'ghi' ), '93a3616263a3646566a3676869' ),
			array( array( 'one' => 1, 'two' => 2 ), '82a36f6e6501a374776f02' ),
		),
	);

	/**
	 * Verify that values are serialized correctly.
	 * @covers ::wfMessagePack
	 */
	public function testMessagePack() {
		foreach( $this->data as $type => $cases ) {
			foreach( $cases as $case ) {
				list( $value, $expected ) = $case;
				$actual = bin2hex( wfMessagePack( $value ) );
				$this->assertEquals( $actual, $expected, $type );
			}
		}
	}
}
