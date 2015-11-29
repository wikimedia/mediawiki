<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 */
abstract class AuthenticationRequestTestCase extends \MediaWikiTestCase {
	/**
	 * @dataProvider provideGetFieldInfo
	 * @param AuthenticationRequest $req
	 */
	public function testGetFieldInfo( $req ) {
		$info = $req->getFieldInfo();
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

	abstract public function provideGetFieldInfo();

	/**
	 * @dataProvider provideLoadFromSubmission
	 * @param string $label
	 * @param AuthenticationRequest $req
	 * @param array $data
	 * @param array|null $expectState
	 */
	public function testLoadFromSubmission( $label, $req, $data, $expectState ) {
		$success = $req->loadFromSubmission( $data );
		if ( !$success ) {
			$this->assertNull( $expectState );
		} elseif ( is_array( $expectState ) ) {
			$clazz = get_class( $req );
			$expect = $clazz::__set_state( $expectState );
			$this->assertEquals( $expect, $req );
		} else {
			$this->assertEquals( $expectState, $req );
		}
	}

	abstract public function provideLoadFromSubmission();
}
