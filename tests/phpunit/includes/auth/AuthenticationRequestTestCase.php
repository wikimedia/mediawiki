<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 */
abstract class AuthenticationRequestTestCase extends \MediaWikiTestCase {
	abstract protected function getInstance( array $args = [] );

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
			$this->assertInstanceOf( 'Message', $data['label'], "Field $field, label" );

			if ( $data['type'] !== 'null' ) {
				$this->assertArrayHasKey( 'help', $data, "Field $field" );
				$this->assertInstanceOf( 'Message', $data['help'], "Field $field, help" );
			}

			if ( isset( $data['optional'] ) ) {
				$this->assertType( 'bool', $data['optional'], "Field $field, optional" );
			}
			if ( isset( $data['image'] ) ) {
				$this->assertType( 'string', $data['image'], "Field $field, image" );
			}
			if ( isset( $data['sensitive'] ) ) {
				$this->assertType( 'bool', $data['sensitive'], "Field $field, sensitive" );
			}
			if ( $data['type'] === 'password' ) {
				$this->assertTrue( !empty( $data['sensitive'] ),
					"Field $field, password field must be sensitive" );
			}

			switch ( $data['type'] ) {
				case 'string':
				case 'password':
				case 'hidden':
					break;
				case 'select':
				case 'multiselect':
					$this->assertArrayHasKey( 'options', $data, "Field $field" );
					$this->assertType( 'array', $data['options'], "Field $field, options" );
					foreach ( $data['options'] as $val => $msg ) {
						$this->assertInstanceOf( 'Message', $msg, "Field $field, option $val" );
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
		return [
			[ [] ]
		];
	}

	/**
	 * @dataProvider provideLoadFromSubmission
	 * @param array $args
	 * @param array $data
	 * @param array|bool $expectState
	 */
	public function testLoadFromSubmission( array $args, array $data, $expectState ) {
		$instance = $this->getInstance( $args );
		$ret = $instance->loadFromSubmission( $data );
		if ( is_array( $expectState ) ) {
			$this->assertTrue( $ret );
			$expect = call_user_func( [ get_class( $instance ), '__set_state' ], $expectState );
			$this->assertEquals( $expect, $instance );
		} else {
			$this->assertFalse( $ret );
		}
	}

	abstract public function provideLoadFromSubmission();
}
