<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLUserTextField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Message\Message;
use MediaWiki\User\UserFactory;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLUserTextField
 */
class HTMLUserTextFieldTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideInputs
	 */
	public function testInputs( array $config, string $value, $expected ) {
		$origUserFactory = $this->getServiceContainer()->getUserFactory();
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromName' )->willReturnCallback( static function ( ...$params ) use ( $origUserFactory ) {
			$user = $origUserFactory->newFromName( ...$params );
			if ( $user ) {
				$user->mId = 0;
				$user->setItemLoaded( 'id' );
			}
			return $user;
		} );
		$this->setService( 'UserFactory', $userFactory );
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

	public static function provideInputs() {
		return [
			'valid username' => [
				[],
				'SomeUser',
				true
			],
			'external username when not allowed' => [
				[],
				'imported>SomeUser',
				'htmlform-user-not-valid'
			],
			'external username when allowed' => [
				[ 'external' => true ],
				'imported>SomeUser',
				true
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
			'valid usemod IP' => [
				[ 'usemodwiki-ipallowed' => true, 'ipallowed' => true, 'exists' => true ],
				'1.2.3.xxx',
				true,
			],
			'valid usemod IP, but not allowed' => [
				[ 'usemodwiki-ipallowed' => false, 'ipallowed' => true, 'exists' => true ],
				'1.2.3.xxx',
				'htmlform-user-not-valid',
			],
			'invalid usemod IP because not enough "x"' => [
				[ 'usemodwiki-ipallowed' => true, 'ipallowed' => true, 'exists' => true ],
				'1.2.3.x',
				'htmlform-user-not-exists',
			],
			'invalid usemod IP because capital "x"' => [
				[ 'usemodwiki-ipallowed' => true, 'ipallowed' => true, 'exists' => true ],
				'1.2.3.XXX',
				'htmlform-user-not-exists',
			],
			'invalid usemod IP because first part not valid IPv4' => [
				[ 'usemodwiki-ipallowed' => true, 'ipallowed' => true, 'exists' => true ],
				'1.2.456.xxx',
				'htmlform-user-not-valid',
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
