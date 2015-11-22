<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 */
abstract class AuthenticationRequestTestCase extends \MediaWikiTestCase {

	abstract protected function getInstance( array $args = array() );

	/**
	 * @dataProvider provideGetFieldInfo
	 */
	public function testGetFieldInfo( array $args ) {
		$info = $this->getInstance( $args )->getFieldInfo();
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

	public static function provideGetFieldInfo() {
		return array(
			array( array() )
		);
	}

	/**
	 * @dataProvider provideLoadFromSubmission
	 * @param string $label
	 * @param array $args
	 * @param array $data
	 * @param array|bool $expectState
	 */
	public function testLoadFromSubmission( $label, array $args, array $data, $expectState ) {
		$instance = $this->getInstance( $args );
		$ret = $instance->loadFromSubmission( $data );
		if ( is_array( $expectState ) ) {
			$this->assertTrue( $ret );
			$expect = call_user_func( array( get_class( $instance ), '__set_state' ), $expectState );
			$this->assertEquals( $expect, $instance );
		} else {
			$this->assertFalse( $ret );
		}
	}

	abstract public function provideLoadFromSubmission();
}
