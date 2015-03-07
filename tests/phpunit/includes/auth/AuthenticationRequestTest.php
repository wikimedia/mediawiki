<?php

/**
 * @group AuthManager
 * @covers AuthenticationRequest
 */
class AuthenticationRequestTest extends MediaWikiTestCase {

	/**
	 * @expectedException BadMethodCallException
	 * @expectedExceptionMessage AuthenticationRequest must override getFieldInfo()
	 */
	public function testGetFieldInfo() {
		AuthenticationRequest::getFieldInfo();
	}

	/**
	 * @expectedException BadMethodCallException
	 * @expectedExceptionMessage AuthenticationRequestMock must override getFieldInfo()
	 */
	public function testGetFieldInfo_subclass() {
		$class = get_class( $this->getMockForAbstractClass(
			'AuthenticationRequest', array(), 'AuthenticationRequestMock'
		) );
		$class::getFieldInfo();
	}

	/**
	 * @dataProvider provideNewFromSubmission
	 * @param string $label
	 * @param array $fieldInfo
	 * @param array $data
	 * @param array|null $expectState
	 */
	public function testNewFromSubmission( $label, $fieldInfo, $data, $expectState ) {
		AuthenticationRequestTest_MockAuthenticationRequest::$fieldInfo = $fieldInfo;
		$ret = AuthenticationRequestTest_MockAuthenticationRequest::newFromSubmission( $data );
		if ( is_array( $expectState ) ) {
			$expect = AuthenticationRequestTest_MockAuthenticationRequest::__set_state( $expectState );
		} else {
			$expect = $expectState;
		}
		$this->assertEquals( $expect, $ret );
	}

	/**
	 * @dataProvider provideNewFromSubmission
	 * @param string $label
	 * @param array $fieldInfo
	 * @param array $data
	 * @param array|null $expectState
	 */
	public function testRequestsFromSubmission( $label, $fieldInfo, $data, $expectState ) {
		AuthenticationRequestTest_MockAuthenticationRequest::$fieldInfo = $fieldInfo;
		$ret = AuthenticationRequest::requestsFromSubmission(
			array( 'AuthenticationRequestTest_MockAuthenticationRequest' ),
			$data,
			'http://example.org/test'
		);

		foreach ( $ret as &$r ) {
			$this->assertEquals( 'http://example.org/test', $r->returnToUrl );
			$r->returnToUrl = null;
		}
		unset( $r );

		if ( is_array( $expectState ) ) {
			$expect = array(
				'AuthenticationRequestTest_MockAuthenticationRequest' =>
					AuthenticationRequestTest_MockAuthenticationRequest::__set_state( $expectState )
			);
		} else {
			$expect = array();
		}

		$this->assertEquals( $expect, $ret );
	}

	public static function provideNewFromSubmission() {
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
}

class AuthenticationRequestTest_MockAuthenticationRequest extends AuthenticationRequest {
	public static $fieldInfo = array();

	public static function getFieldInfo() {
		return self::$fieldInfo;
	}
}
