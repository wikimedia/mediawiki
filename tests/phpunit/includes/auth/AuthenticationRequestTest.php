<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\AuthenticationRequest
 */
class AuthenticationRequestTest extends \MediaWikiTestCase {

	public function testBasics() {
		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );

		$this->assertSame( get_class( $mock ), $mock->getUniqueId() );

		$ret = $mock->describeCredentials();
		$this->assertInternalType( 'array', $ret );
		$this->assertArrayHasKey( 'provider', $ret );
		$this->assertInstanceOf( 'Message', $ret['provider'] );
		$this->assertArrayHasKey( 'account', $ret );
		$this->assertInstanceOf( 'Message', $ret['account'] );
	}

	public function testLoadRequestsFromSubmission() {
		$mb = $this->getMockBuilder( 'MediaWiki\\Auth\\AuthenticationRequest' )
			->setMethods( array( 'loadFromSubmission' ) );

		$data = array( 'foo', 'bar' );

		$req1 = $mb->getMockForAbstractClass();
		$req1->expects( $this->once() )->method( 'loadFromSubmission' )
			->with( $this->identicalTo( $data ) )
			->will( $this->returnValue( false ) );

		$req2 = $mb->getMockForAbstractClass();
		$req2->expects( $this->once() )->method( 'loadFromSubmission' )
			->with( $this->identicalTo( $data ) )
			->will( $this->returnValue( true ) );

		$this->assertSame(
			array( $req2 ),
			AuthenticationRequest::loadRequestsFromSubmission( array( $req1, $req2 ), $data )
		);
	}

	public function testGetRequestByClass() {
		$mb = $this->getMockBuilder(
			'MediaWiki\\Auth\\AuthenticationRequest',
			'AuthenticationRequestTest_AuthenticationRequest2'
		);

		$reqs = array(
			$this->getMockForAbstractClass(
				'MediaWiki\\Auth\\AuthenticationRequest',
				array(),
				'AuthenticationRequestTest_AuthenticationRequest1'
			),
			$mb->getMockForAbstractClass(),
			$mb->getMockForAbstractClass(),
		);

		$this->assertNull( AuthenticationRequest::getRequestByClass(
			$reqs, 'AuthenticationRequestTest_AuthenticationRequest0'
		) );
		$this->assertSame( $reqs[0], AuthenticationRequest::getRequestByClass(
			$reqs, 'AuthenticationRequestTest_AuthenticationRequest1'
		) );
		$this->assertNull( AuthenticationRequest::getRequestByClass(
			$reqs, 'AuthenticationRequestTest_AuthenticationRequest2'
		) );
	}

	public function testGetUsernameFromRequests() {
		$mb = $this->getMockBuilder( 'MediaWiki\\Auth\\AuthenticationRequest' );

		for ( $i = 0; $i < 3; $i++ ) {
			$req = $mb->getMockForAbstractClass();
			$req->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( array(
				'username' => array(
					'type' => 'string',
				),
			) ) );
			$reqs[] = $req;
		}

		$req = $mb->getMockForAbstractClass();
		$req->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( array() ) );
		$req->username = 'baz';
		$reqs[] = $req;

		$this->assertNull( AuthenticationRequest::getUsernameFromRequests( $reqs ) );

		$reqs[1]->username = 'foo';
		$this->assertSame( 'foo', AuthenticationRequest::getUsernameFromRequests( $reqs ) );

		$reqs[0]->username = 'foo';
		$reqs[2]->username = 'foo';
		$this->assertSame( 'foo', AuthenticationRequest::getUsernameFromRequests( $reqs ) );

		$reqs[1]->username = 'bar';
		try {
			AuthenticationRequest::getUsernameFromRequests( $reqs );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'Conflicting username fields: "bar" from ' .
					get_class( $reqs[1] ) . '::$username vs. "foo" from ' .
					get_class( $reqs[0] ) . '::$username',
				$ex->getMessage()
			);
		}
	}

	public function testMergeFieldInfo() {
		$msg = wfMessage( 'foo' );

		$req1 = $this->getMock( 'MediaWiki\\Auth\\AuthenticationRequest' );
		$req1->required = AuthenticationRequest::REQUIRED;
		$req1->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( array(
			'string1' => array(
				'type' => 'string',
				'label' => $msg,
				'help' => $msg,
			),
			'string2' => array(
				'type' => 'string',
				'label' => $msg,
				'help' => $msg,
			),
			'optional' => array(
				'type' => 'string',
				'label' => $msg,
				'help' => $msg,
				'optional' => true,
			),
			'select' => array(
				'type' => 'select',
				'options' => array( 'foo' => $msg, 'baz' => $msg ),
				'label' => $msg,
				'help' => $msg,
			),
		) ) );

		$req2 = $this->getMock( 'MediaWiki\\Auth\\AuthenticationRequest' );
		$req2->required = AuthenticationRequest::REQUIRED;
		$req2->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( array(
			'string1' => array(
				'type' => 'string',
				'label' => $msg,
				'help' => $msg,
			),
			'string3' => array(
				'type' => 'string',
				'label' => $msg,
				'help' => $msg,
			),
			'select' => array(
				'type' => 'select',
				'options' => array( 'bar' => $msg, 'baz' => $msg ),
				'label' => $msg,
				'help' => $msg,
			),
		) ) );

		$req3 = $this->getMock( 'MediaWiki\\Auth\\AuthenticationRequest' );
		$req3->required = AuthenticationRequest::REQUIRED;
		$req3->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( array(
			'string1' => array(
				'type' => 'checkbox',
				'label' => $msg,
				'help' => $msg,
			),
		) ) );

		$req4 = $this->getMock( 'MediaWiki\\Auth\\AuthenticationRequest' );
		$req4->required = AuthenticationRequest::REQUIRED;
		$req4->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( array() ) );

		// Basic combining

		$fields = AuthenticationRequest::mergeFieldInfo( array( $req1 ) );
		$expect = $req1->getFieldInfo();
		foreach ( $expect as $name => &$options ) {
			$options['optional'] = !empty( $options['optional'] );
		}
		unset( $options );
		$this->assertEquals( $expect, $fields );

		$fields = AuthenticationRequest::mergeFieldInfo( array( $req1, $req4 ) );
		$this->assertEquals( $expect, $fields );

		try {
			AuthenticationRequest::mergeFieldInfo( array( $req1, $req3 ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'Field type conflict for "string1", "string" vs "checkbox"',
				$ex->getMessage()
			);
		}

		$fields = AuthenticationRequest::mergeFieldInfo( array( $req1, $req2 ) );
		$expect += $req2->getFieldInfo();
		$expect['string2']['optional'] = false;
		$expect['string3']['optional'] = false;
		$expect['select']['options']['bar'] = $msg;
		$this->assertEquals( $expect, $fields );

		// Combining with something not required

		$req1->required = AuthenticationRequest::PRIMARY_REQUIRED;

		$fields = AuthenticationRequest::mergeFieldInfo( array( $req1 ) );
		$expect = $req1->getFieldInfo();
		foreach ( $expect as $name => &$options ) {
			$options['optional'] = true;
		}
		unset( $options );
		$this->assertEquals( $expect, $fields );

		$fields = AuthenticationRequest::mergeFieldInfo( array( $req1, $req2 ) );
		$expect += $req2->getFieldInfo();
		$expect['string1']['optional'] = false;
		$expect['string3']['optional'] = false;
		$expect['select']['optional'] = false;
		$expect['select']['options']['bar'] = $msg;
		$this->assertEquals( $expect, $fields );
	}

	/**
	 * @dataProvider provideLoadFromSubmission
	 * @param string $label
	 * @param array $fieldInfo
	 * @param array $data
	 * @param array|bool $expectState
	 */
	public function testLoadFromSubmission( $label, $fieldInfo, $data, $expectState ) {
		$mock = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );
		$mock->expects( $this->any() )->method( 'getFieldInfo' )
			->will( $this->returnValue( $fieldInfo ) );

		$ret = $mock->loadFromSubmission( $data );
		if ( is_array( $expectState ) ) {
			$this->assertTrue( $ret );
			$expect = call_user_func( array( get_class( $mock ), '__set_state' ), $expectState );
			$this->assertEquals( $expect, $mock );
		} else {
			$this->assertFalse( $ret );
		}
	}

	public static function provideLoadFromSubmission() {
		return array(
			array(
				'No fields',
				array(),
				$data = array( 'foo' => 'bar' ),
				false
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
				false
			),
			array(
				'Simple field, empty',
				array(
					'field' => array(
						'type' => 'string',
					),
				),
				array( 'field' => '' ),
				false
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
				false
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
				false
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
				false
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
				false
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
				false
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
				false
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
