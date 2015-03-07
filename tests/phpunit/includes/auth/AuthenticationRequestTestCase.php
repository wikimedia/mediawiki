<?php

/**
 * @group AuthManager
 */
abstract class AuthenticationRequestTestCase extends MediaWikiTestCase {
	protected static $class = null;

	public function testGetFieldInfo() {
		$info = call_user_func( array( static::$class, 'getFieldInfo' ) );
		$this->assertType( 'array', $info );

		foreach ( $info as $field => $data ) {
			$this->assertType( 'array', $data, "Field $field" );
			$this->assertArrayHasKey( 'type', $data, "Field $field" );
			$this->assertArrayHasKey( 'label', $data, "Field $field" );
			$this->assertInstanceOf( 'MessageSpecifier', $data['label'], "Field $field, label" );

			if ( $data['type'] !== 'null' ) {
				$this->assertArrayHasKey( 'help', $data, "Field $field" );
				$this->assertInstanceOf( 'MessageSpecifier', $data['help'], "Field $field, help" );
			}

			if ( isset( $data['optional'] ) ) {
				$this->assertType( 'bool', $data['optional'], "Field $field, optional" );
			}
			if ( isset( $data['image'] ) ) {
				$this->assertType( 'string', $data['image'], "Field $field, image" );
			}

			switch ( $data['type'] ) {
				case 'string':
				case 'password':
					break;
				case 'select':
				case 'multiselect':
					$this->assertArrayHasKey( 'options', $data, "Field $field" );
					$this->assertType( 'array', $data['options'], "Field $field, options" );
					foreach ( $data['options'] as $val => $msg ) {
						$this->assertInstanceOf( 'MessageSpecifier', $msg, "Field $field, option $val" );
					}
					break;
				case 'checkbox':
					break;
				case 'button':
					break;
				case 'null':
					break;
				default:
					$this->fail( "Field $field, unknown type " . $data['type'] );
					break;
			}
		}
	}

	/**
	 * @dataProvider provideNewFromSubmission
	 * @uses AuthenticationRequest
	 * @param string $label
	 * @param array $data
	 * @param array|null $expectState
	 */
	public function testNewFromSubmission( $label, $data, $expectState ) {
		if ( is_array( $expectState ) ) {
			$expect = call_user_func( array( static::$class, '__set_state' ), $expectState );
		} else {
			$expect = $expectState;
		}
		$ret = call_user_func( array( static::$class, 'newFromSubmission' ), $data );
		$this->assertEquals( $expect, $ret );
	}

	abstract public function provideNewFromSubmission();
}
