<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AuthenticationRequest
 */
class AuthenticationRequestTest extends \MediaWikiTestCase {
	public function testBasics() {
		$mock = $this->getMockForAbstractClass( AuthenticationRequest::class );

		$this->assertSame( get_class( $mock ), $mock->getUniqueId() );

		$this->assertType( 'array', $mock->getMetadata() );

		$ret = $mock->describeCredentials();
		$this->assertInternalType( 'array', $ret );
		$this->assertArrayHasKey( 'provider', $ret );
		$this->assertInstanceOf( \Message::class, $ret['provider'] );
		$this->assertArrayHasKey( 'account', $ret );
		$this->assertInstanceOf( \Message::class, $ret['account'] );
	}

	public function testLoadRequestsFromSubmission() {
		$mb = $this->getMockBuilder( AuthenticationRequest::class )
			->setMethods( [ 'loadFromSubmission' ] );

		$data = [ 'foo', 'bar' ];

		$req1 = $mb->getMockForAbstractClass();
		$req1->expects( $this->once() )->method( 'loadFromSubmission' )
			->with( $this->identicalTo( $data ) )
			->will( $this->returnValue( false ) );

		$req2 = $mb->getMockForAbstractClass();
		$req2->expects( $this->once() )->method( 'loadFromSubmission' )
			->with( $this->identicalTo( $data ) )
			->will( $this->returnValue( true ) );

		$this->assertSame(
			[ $req2 ],
			AuthenticationRequest::loadRequestsFromSubmission( [ $req1, $req2 ], $data )
		);
	}

	public function testGetRequestByClass() {
		$mb = $this->getMockBuilder(
			AuthenticationRequest::class, 'AuthenticationRequestTest_AuthenticationRequest2'
		);

		$reqs = [
			$this->getMockForAbstractClass(
				AuthenticationRequest::class, [], 'AuthenticationRequestTest_AuthenticationRequest1'
			),
			$mb->getMockForAbstractClass(),
			$mb->getMockForAbstractClass(),
			$this->getMockForAbstractClass(
				PasswordAuthenticationRequest::class, [],
				'AuthenticationRequestTest_PasswordAuthenticationRequest'
			),
		];

		$this->assertNull( AuthenticationRequest::getRequestByClass(
			$reqs, 'AuthenticationRequestTest_AuthenticationRequest0'
		) );
		$this->assertSame( $reqs[0], AuthenticationRequest::getRequestByClass(
			$reqs, 'AuthenticationRequestTest_AuthenticationRequest1'
		) );
		$this->assertNull( AuthenticationRequest::getRequestByClass(
			$reqs, 'AuthenticationRequestTest_AuthenticationRequest2'
		) );
		$this->assertNull( AuthenticationRequest::getRequestByClass(
			$reqs, PasswordAuthenticationRequest::class
		) );
		$this->assertNull( AuthenticationRequest::getRequestByClass(
			$reqs, 'ClassThatDoesNotExist'
		) );

		$this->assertNull( AuthenticationRequest::getRequestByClass(
			$reqs, 'AuthenticationRequestTest_AuthenticationRequest0', true
		) );
		$this->assertSame( $reqs[0], AuthenticationRequest::getRequestByClass(
			$reqs, 'AuthenticationRequestTest_AuthenticationRequest1', true
		) );
		$this->assertNull( AuthenticationRequest::getRequestByClass(
			$reqs, 'AuthenticationRequestTest_AuthenticationRequest2', true
		) );
		$this->assertSame( $reqs[3], AuthenticationRequest::getRequestByClass(
			$reqs, PasswordAuthenticationRequest::class, true
		) );
		$this->assertNull( AuthenticationRequest::getRequestByClass(
			$reqs, 'ClassThatDoesNotExist', true
		) );
	}

	public function testGetUsernameFromRequests() {
		$mb = $this->getMockBuilder( AuthenticationRequest::class );

		for ( $i = 0; $i < 3; $i++ ) {
			$req = $mb->getMockForAbstractClass();
			$req->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( [
				'username' => [
					'type' => 'string',
				],
			] ) );
			$reqs[] = $req;
		}

		$req = $mb->getMockForAbstractClass();
		$req->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( [] ) );
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

		$req1 = $this->createMock( AuthenticationRequest::class );
		$req1->required = AuthenticationRequest::REQUIRED;
		$req1->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( [
			'string1' => [
				'type' => 'string',
				'label' => $msg,
				'help' => $msg,
			],
			'string2' => [
				'type' => 'string',
				'label' => $msg,
				'help' => $msg,
			],
			'optional' => [
				'type' => 'string',
				'label' => $msg,
				'help' => $msg,
				'optional' => true,
			],
			'select' => [
				'type' => 'select',
				'options' => [ 'foo' => $msg, 'baz' => $msg ],
				'label' => $msg,
				'help' => $msg,
			],
		] ) );

		$req2 = $this->createMock( AuthenticationRequest::class );
		$req2->required = AuthenticationRequest::REQUIRED;
		$req2->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( [
			'string1' => [
				'type' => 'string',
				'label' => $msg,
				'help' => $msg,
				'sensitive' => true,
			],
			'string3' => [
				'type' => 'string',
				'label' => $msg,
				'help' => $msg,
			],
			'select' => [
				'type' => 'select',
				'options' => [ 'bar' => $msg, 'baz' => $msg ],
				'label' => $msg,
				'help' => $msg,
			],
		] ) );

		$req3 = $this->createMock( AuthenticationRequest::class );
		$req3->required = AuthenticationRequest::REQUIRED;
		$req3->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( [
			'string1' => [
				'type' => 'checkbox',
				'label' => $msg,
				'help' => $msg,
			],
		] ) );

		$req4 = $this->createMock( AuthenticationRequest::class );
		$req4->required = AuthenticationRequest::REQUIRED;
		$req4->expects( $this->any() )->method( 'getFieldInfo' )->will( $this->returnValue( [] ) );

		// Basic combining

		$fields = AuthenticationRequest::mergeFieldInfo( [ $req1 ] );
		$expect = $req1->getFieldInfo();
		foreach ( $expect as $name => &$options ) {
			$options['optional'] = !empty( $options['optional'] );
			$options['sensitive'] = !empty( $options['sensitive'] );
		}
		unset( $options );
		$this->assertEquals( $expect, $fields );

		$fields = AuthenticationRequest::mergeFieldInfo( [ $req1, $req4 ] );
		$this->assertEquals( $expect, $fields );

		try {
			AuthenticationRequest::mergeFieldInfo( [ $req1, $req3 ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'Field type conflict for "string1", "string" vs "checkbox"',
				$ex->getMessage()
			);
		}

		$fields = AuthenticationRequest::mergeFieldInfo( [ $req1, $req2 ] );
		$expect += $req2->getFieldInfo();
		$expect['string1']['sensitive'] = true;
		$expect['string2']['optional'] = false;
		$expect['string3']['optional'] = false;
		$expect['string3']['sensitive'] = false;
		$expect['select']['options']['bar'] = $msg;
		$this->assertEquals( $expect, $fields );

		// Combining with something not required

		$req1->required = AuthenticationRequest::PRIMARY_REQUIRED;

		$fields = AuthenticationRequest::mergeFieldInfo( [ $req1, $req2 ] );
		$expect += $req2->getFieldInfo();
		$expect['string1']['optional'] = false;
		$expect['string1']['sensitive'] = true;
		$expect['string3']['optional'] = false;
		$expect['select']['optional'] = false;
		$expect['select']['options']['bar'] = $msg;
		$this->assertEquals( $expect, $fields );

		$req2->required = AuthenticationRequest::PRIMARY_REQUIRED;

		$fields = AuthenticationRequest::mergeFieldInfo( [ $req1, $req2 ] );
		$expect = $req1->getFieldInfo() + $req2->getFieldInfo();
		foreach ( $expect as $name => &$options ) {
			$options['sensitive'] = !empty( $options['sensitive'] );
		}
		$expect['string1']['optional'] = false;
		$expect['string1']['sensitive'] = true;
		$expect['string2']['optional'] = true;
		$expect['string3']['optional'] = true;
		$expect['select']['optional'] = false;
		$expect['select']['options']['bar'] = $msg;
		$this->assertEquals( $expect, $fields );
	}

	/**
	 * @dataProvider provideLoadFromSubmission
	 * @param array $fieldInfo
	 * @param array $data
	 * @param array|bool $expectState
	 */
	public function testLoadFromSubmission( $fieldInfo, $data, $expectState ) {
		$mock = $this->getMockForAbstractClass( AuthenticationRequest::class );
		$mock->expects( $this->any() )->method( 'getFieldInfo' )
			->will( $this->returnValue( $fieldInfo ) );

		$ret = $mock->loadFromSubmission( $data );
		if ( is_array( $expectState ) ) {
			$this->assertTrue( $ret );
			$expect = call_user_func( [ get_class( $mock ), '__set_state' ], $expectState );
			$this->assertEquals( $expect, $mock );
		} else {
			$this->assertFalse( $ret );
		}
	}

	public static function provideLoadFromSubmission() {
		return [
			'No fields' => [
				[],
				$data = [ 'foo' => 'bar' ],
				false
			],

			'Simple field' => [
				[
					'field' => [
						'type' => 'string',
					],
				],
				$data = [ 'field' => 'string!' ],
				$data
			],
			'Simple field, not supplied' => [
				[
					'field' => [
						'type' => 'string',
					],
				],
				[],
				false
			],
			'Simple field, empty' => [
				[
					'field' => [
						'type' => 'string',
					],
				],
				[ 'field' => '' ],
				false
			],
			'Simple field, optional, not supplied' => [
				[
					'field' => [
						'type' => 'string',
						'optional' => true,
					],
				],
				[],
				false
			],
			'Simple field, optional, empty' => [
				[
					'field' => [
						'type' => 'string',
						'optional' => true,
					],
				],
				$data = [ 'field' => '' ],
				$data
			],

			'Checkbox, checked' => [
				[
					'check' => [
						'type' => 'checkbox',
					],
				],
				[ 'check' => '' ],
				[ 'check' => true ]
			],
			'Checkbox, unchecked' => [
				[
					'check' => [
						'type' => 'checkbox',
					],
				],
				[],
				false
			],
			'Checkbox, optional, unchecked' => [
				[
					'check' => [
						'type' => 'checkbox',
						'optional' => true,
					],
				],
				[],
				[ 'check' => false ]
			],

			'Button, used' => [
				[
					'push' => [
						'type' => 'button',
					],
				],
				[ 'push' => '' ],
				[ 'push' => true ]
			],
			'Button, unused' => [
				[
					'push' => [
						'type' => 'button',
					],
				],
				[],
				false
			],
			'Button, optional, unused' => [
				[
					'push' => [
						'type' => 'button',
						'optional' => true,
					],
				],
				[],
				[ 'push' => false ]
			],
			'Button, image-style' => [
				[
					'push' => [
						'type' => 'button',
					],
				],
				[ 'push_x' => 0, 'push_y' => 0 ],
				[ 'push' => true ]
			],

			'Select' => [
				[
					'choose' => [
						'type' => 'select',
						'options' => [
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						],
					],
				],
				$data = [ 'choose' => 'foo' ],
				$data
			],
			'Select, invalid choice' => [
				[
					'choose' => [
						'type' => 'select',
						'options' => [
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						],
					],
				],
				$data = [ 'choose' => 'baz' ],
				false
			],
			'Multiselect (2)' => [
				[
					'choose' => [
						'type' => 'multiselect',
						'options' => [
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						],
					],
				],
				$data = [ 'choose' => [ 'foo', 'bar' ] ],
				$data
			],
			'Multiselect (1)' => [
				[
					'choose' => [
						'type' => 'multiselect',
						'options' => [
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						],
					],
				],
				$data = [ 'choose' => [ 'bar' ] ],
				$data
			],
			'Multiselect, string for some reason' => [
				[
					'choose' => [
						'type' => 'multiselect',
						'options' => [
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						],
					],
				],
				[ 'choose' => 'foo' ],
				[ 'choose' => [ 'foo' ] ]
			],
			'Multiselect, invalid choice' => [
				[
					'choose' => [
						'type' => 'multiselect',
						'options' => [
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						],
					],
				],
				[ 'choose' => [ 'foo', 'baz' ] ],
				false
			],
			'Multiselect, empty' => [
				[
					'choose' => [
						'type' => 'multiselect',
						'options' => [
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						],
					],
				],
				[ 'choose' => [] ],
				false
			],
			'Multiselect, optional, nothing submitted' => [
				[
					'choose' => [
						'type' => 'multiselect',
						'options' => [
							'foo' => wfMessage( 'mainpage' ),
							'bar' => wfMessage( 'mainpage' ),
						],
						'optional' => true,
					],
				],
				[],
				[ 'choose' => [] ]
			],
		];
	}
}
