<?php

namespace MediaWiki\Auth;

use BadMethodCallException;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\AuthenticationRequest
 */
class AuthenticationRequestTest extends \MediaWikiTestCase {

	/**
	 * @expectedException BadMethodCallException
	 * @expectedExceptionMessage AuthenticationRequestMock must override getFieldInfo()
	 */
	public function testGetFieldInfo_subclass() {
		$request = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AuthenticationRequest', array(), 'AuthenticationRequestMock'
		);
		$request->getFieldInfo();
	}

	/**
	 * @dataProvider provideLoadFromSubmission
	 * @param string $label
	 * @param array $fieldInfo
	 * @param array $data
	 * @param array|null $expectState
	 */
	public function testLoadFromSubmission( $label, $fieldInfo, $data, $expectState ) {
		AuthenticationRequestTestMockAuthenticationRequest::$fieldInfo = $fieldInfo;
		$request = new AuthenticationRequestTestMockAuthenticationRequest();
		$success = $request->loadFromSubmission( $data );
		if ( $success === false ) {
			$this->assertNull( $expectState );
		} elseif ( is_array( $expectState ) ) {
			$expect = AuthenticationRequestTestMockAuthenticationRequest::__set_state( $expectState );
			$this->assertEquals( $expect, $request );
		} else {
			$this->assertEquals( $expectState, $request );
		}
	}

	public static function provideLoadFromSubmission() {
		return array(
			array(
				'No fields',
				array(),
				$data = array( 'foo' => 'bar' ),
				null
			),

			array(
				'Simple field',
				array(
					'field' => array(
						'type' => 'string',
					),
				),
				$data = array( 'field' => 'string!' ),
				$data
			),
			array(
				'Simple field, not supplied',
				array(
					'field' => array(
						'type' => 'string',
					),
				),
				array(),
				null
			),
			array(
				'Simple field, empty',
				array(
					'field' => array(
						'type' => 'string',
					),
				),
				array( 'field' => '' ),
				null
			),
			array(
				'Simple field, optional, not supplied',
				array(
					'field' => array(
						'type' => 'string',
						'optional' => true,
					),
				),
				array(),
				null
			),
			array(
				'Simple field, optional, empty',
				array(
					'field' => array(
						'type' => 'string',
						'optional' => true,
					),
				),
				$data = array( 'field' => '' ),
				$data
			),

			array(
				'Checkbox, checked',
				array(
					'check' => array(
						'type' => 'checkbox',
					),
				),
				array( 'check' => '' ),
				array( 'check' => true )
			),
			array(
				'Checkbox, unchecked',
				array(
					'check' => array(
						'type' => 'checkbox',
					),
				),
				array(),
				null
			),
			array(
				'Checkbox, optional, unchecked',
				array(
					'check' => array(
						'type' => 'checkbox',
						'optional' => true,
					),
				),
				array(),
				array( 'check' => false )
			),

			array(
				'Button, used',
				array(
					'push' => array(
						'type' => 'button',
					),
				),
				array( 'push' => '' ),
				array( 'push' => true )
			),
			array(
				'Button, unused',
				array(
					'push' => array(
						'type' => 'button',
					),
				),
				array(),
				null
			),
			array(
				'Button, optional, unused',
				array(
					'push' => array(
						'type' => 'button',
						'optional' => true,
					),
				),
				array(),
				array( 'push' => false )
			),
			array(
				'Button, image-style',
				array(
					'push' => array(
						'type' => 'button',
					),
				),
				array( 'push_x' => 0, 'push_y' => 0 ),
				array( 'push' => true )
			),

			array(
				'Select',
				array(
					'choose' => array(
						'type' => 'select',
						'options' => array(
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						),
					),
				),
				$data = array( 'choose' => 'foo' ),
				$data
			),
			array(
				'Select, invalid choice',
				array(
					'choose' => array(
						'type' => 'select',
						'options' => array(
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						),
					),
				),
				$data = array( 'choose' => 'baz' ),
				null
			),
			array(
				'Multiselect (2)',
				array(
					'choose' => array(
						'type' => 'multiselect',
						'options' => array(
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						),
					),
				),
				$data = array( 'choose' => array( 'foo', 'bar' ) ),
				$data
			),
			array(
				'Multiselect (1)',
				array(
					'choose' => array(
						'type' => 'multiselect',
						'options' => array(
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						),
					),
				),
				$data = array( 'choose' => array( 'bar' ) ),
				$data
			),
			array(
				'Multiselect, string for some reason',
				array(
					'choose' => array(
						'type' => 'multiselect',
						'options' => array(
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						),
					),
				),
				array( 'choose' => 'foo' ),
				array( 'choose' => array( 'foo' ) )
			),
			array(
				'Multiselect, invalid choice',
				array(
					'choose' => array(
						'type' => 'multiselect',
						'options' => array(
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						),
					),
				),
				array( 'choose' => array( 'foo', 'baz' ) ),
				null
			),
			array(
				'Multiselect, empty',
				array(
					'choose' => array(
						'type' => 'multiselect',
						'options' => array(
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						),
					),
				),
				array( 'choose' => array() ),
				null
			),
			array(
				'Multiselect, optional, nothing submitted',
				array(
					'choose' => array(
						'type' => 'multiselect',
						'options' => array(
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						),
						'optional' => true,
					),
				),
				array(),
				array( 'choose' => array() )
			),
		);
	}

	public function testGetRequestByClass() {
		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest', array(),
			'AbstractAuthenticationProviderRequest1' );
		$this->getMockClass( 'MediaWiki\\Auth\\AuthenticationRequest', array(), array(),
			'AbstractAuthenticationProviderRequest2' );
		$mock2 = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest', array(),
			'AbstractAuthenticationProviderRequest3' );
		$requests = array(
			$mock,
			new \AbstractAuthenticationProviderRequest2(),
			new \AbstractAuthenticationProviderRequest2(),
			$mock2,
		);

		$this->assertEquals( null, AuthenticationRequest::getRequestByClass( $requests,
			'AbstractAuthenticationProviderRequest0' ) );
		$this->assertEquals( $mock, AuthenticationRequest::getRequestByClass( $requests,
			'AbstractAuthenticationProviderRequest1' ) );
		$this->assertEquals( null, AuthenticationRequest::getRequestByClass( $requests,
			'AbstractAuthenticationProviderRequest2' ) );
		$this->assertEquals( $mock2, AuthenticationRequest::getRequestByClass( $requests,
			'AbstractAuthenticationProviderRequest3' ) );
	}
}

class AuthenticationRequestTestMockAuthenticationRequest extends AuthenticationRequest {
	public static $fieldInfo = array();

	public function getFieldInfo() {
		return self::$fieldInfo;
	}
}
