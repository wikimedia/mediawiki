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

class AvroValidatorTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		if ( !class_exists( 'AvroSchema' ) ) {
			$this->markTestSkipped( 'Avro is required to run the AvroValidatorTest' );
		}
		parent::setUp();
	}

	public function getErrorsProvider() {
		$stringSchema = AvroSchema::parse( json_encode( array( 'type' => 'string' ) ) );
		$recordSchema = AvroSchema::parse( json_encode( array(
			'type' => 'record',
			'name' => 'ut',
			'fields' => array(
				array( 'name' => 'id', 'type' => 'int', 'required' => true ),
			),
		) ) );
		$enumSchema = AvroSchema::parse( json_encode( array(
			'type' => 'record',
			'name' => 'ut',
			'fields' => array(
				array( 'name' => 'count', 'type' => array( 'int', 'null' ) ),
			),
		) ) );

		return array(
			array(
				'No errors with a simple string serialization',
				$stringSchema, 'foobar', array(),
			),

			array(
				'Cannot serialize integer into string',
				$stringSchema, 5, 'Expected string, but recieved integer',
			),

			array(
				'Cannot serialize array into string',
				$stringSchema, array(), 'Expected string, but recieved array',
			),

			array(
				'allows and ignores extra fields',
				$recordSchema, array( 'id' => 4, 'foo' => 'bar' ), array(),
			),

			array(
				'detects missing fields',
				$recordSchema, array(), array( 'id' => 'Missing expected field' ),
			),

			array(
				'handles first element in enum',
				$enumSchema, array( 'count' => 4 ), array(),
			),

			array(
				'handles second element in enum',
				$enumSchema, array( 'count' => null ), array(),
			),

			array(
				'rejects element not in union',
				$enumSchema, array( 'count' => 'invalid' ), array( 'count' => array(
					'Expected any one of these to be true',
					array(
						'Expected integer, but recieved string',
						'Expected null, but recieved string',
					)
				) )
			),
		);
	}

	/**
	 * @dataProvider getErrorsProvider
	 */
	public function testGetErrors( $message, $schema, $datum, $expected ) {
		$this->assertEquals(
			$expected,
			AvroValidator::getErrors( $schema, $datum ),
			$message
		);
	}
}
