<?php

/**
 * @covers HTMLUserTextFieldTest
 */
class HTMLUserTextFieldTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideInputs
	 */
	public function testInputs( array $config, string $value, $expected ) {
		$htmlForm = $this->createMock( HTMLForm::class );
		$htmlForm->method( 'msg' )->willReturnCallback( 'wfMessage' );

		$field = new HTMLUserTextField( $config + [ 'fieldname' => 'foo', 'parent' => $htmlForm ] );
		$result = $field->validate( $value, [ 'foo' => $value ] );
		if ( $result instanceof Message ) {
			$this->assertSame( $expected, $result->getKey() );
		} else {
			$this->assertSame( $expected, $result );
		}
	}

	public function provideInputs() {
		return [
			'valid username' => [
				[],
				'SomeUser',
				true
			],
			'invalid username' => [
				[],
				'<SomeUser>',
				'htmlform-user-not-valid'
			],
			'valid IP' => [
				[ 'ipallowed' => true ],
				'1.2.3.4',
				true
			],
			'valid IP, but not allowed' => [
				[ 'ipallowed' => false ],
				'1.2.3.4',
				'htmlform-user-not-valid'
			],
			'invalid IP' => [
				[ 'ipallowed' => true ],
				'1.2.3.456',
				'htmlform-user-not-valid'
			],
			'valid IP range' => [
				[ 'iprange' => true ],
				'1.2.3.4/30',
				true
			],
			'valid IP range, but not allowed' => [
				[ 'iprange' => false ],
				'1.2.3.4/30',
				'htmlform-user-not-valid'
			],
			'invalid IP range (bad syntax)' => [
				[ 'iprange' => true ],
				'1.2.3.4/x',
				'htmlform-user-not-valid'
			],
			'invalid IP range (exceeds limits)' => [
				[
					'iprange' => true,
					'iprangelimits' => [
						'IPv4' => 11,
						'IPv6' => 11,
					],
				],
				'1.2.3.4/10',
				'ip_range_exceeded'
			],
			'valid username, but does not exist' => [
				[ 'exists' => true ],
				'SomeUser',
				'htmlform-user-not-exists'
			],
		];
	}

}
