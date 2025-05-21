<?php

namespace MediaWiki\Tests\ParamValidator\TypeDef;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers \MediaWiki\ParamValidator\TypeDef\UserDef
 */
class UserDefTest extends TypeDefUnitTestCase {
	use DummyServicesTrait;

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		// The UserIdentityLookup that we have knows about 5 users, with ids
		// 1 through 5 and names starting with the first 5 letters of the alphabet
		// And 1 temporary account with a larger id separate from this pattern:
		$namesToIds = [
			'Adam Smith' => 1,
			'Becca' => 2,
			'Charlie' => 3,
			'Danny' => 4,
			'Emma' => 5,
			'*Unregistered 1' => 100,
		];
		$userIdentityLookup = $this->createMock( UserIdentityLookup::class );
		$userIdentityLookup->method( 'getUserIdentityByName' )->willReturnCallback(
			static function ( $name, $flags ) use ( $namesToIds ) {
				if ( isset( $namesToIds[$name] ) ) {
					return new UserIdentityValue( $namesToIds[$name], $name );
				}
				return null;
			}
		);
		$userIdentityLookup->method( 'getUserIdentityByUserId' )->willReturnCallback(
			static function ( $id, $flags ) use ( $namesToIds ) {
				$idsToNames = array_flip( $namesToIds );
				if ( isset( $idsToNames[$id] ) ) {
					return new UserIdentityValue( $id, $idsToNames[$id] );
				}
				return null;
			}
		);

		// DummyServicesTrait will call $this->createHookContainer() if we didn't pass
		// one, but that method is only available from MediaWikiTestCaseTrait - just
		// create a simple mock that doesn't do anything, because we
		// don't care about hooks here
		$hookContainer = $this->createMock( HookContainer::class );
		$hookContainer->method( 'run' )->willReturn( true );
		// We can throw mock exceptions because the UserDef code doesn't care about
		// the messages in the exceptions, just if they are thrown
		$titleParser = $this->getDummyTitleParser( [
			'validInterwikis' => [ 'interwiki' ],
			'throwMockExceptions' => true,
			'hookContainer' => $hookContainer, // for the NamespaceInfo
		] );
		$userNameUtils = $this->getDummyUserNameUtils( [
			'titleParser' => $titleParser, // don't create a new one
			'hookContainer' => $hookContainer,
		] );
		return new UserDef(
			$callbacks,
			$userIdentityLookup,
			$titleParser,
			$userNameUtils
		);
	}

	public static function provideValidate() {
		// General tests of string inputs
		$data = [
			'Basic' => [ 'name', 'Adam Smith', 'Adam Smith' ],
			'Normalized' => [ 'name', 'adam_Smith', 'Adam Smith' ],
			'External' => [ 'interwiki', 'm>some_user', 'm>some_user' ],
			'Temporary user' => [ 'temp', '*Unregistered 1', '*Unregistered 1' ],
			'Temporary user which does not exist' => [ 'temp', '*Unregistered 123456', '*Unregistered 123456' ],
			'Temporary user which is not normalised' => [ 'temp', '*Unregistered_123456', '*Unregistered 123456' ],
			'IPv4' => [ 'ip', '192.168.0.1', '192.168.0.1' ],
			'IPv4, normalized' => [ 'ip', '192.168.000.001', '192.168.0.1' ],
			'IPv6' => [ 'ip', '2001:DB8:0:0:0:0:0:0', '2001:DB8:0:0:0:0:0:0' ],
			'IPv6, normalized' => [ 'ip', '2001:0db8::', '2001:DB8:0:0:0:0:0:0' ],
			'IPv6, with leading ::' => [ 'ip', '::1', '0:0:0:0:0:0:0:1' ],
			'IPv4 range' => [ 'cidr', '192.168.000.000/16', '192.168.0.0/16' ],
			'IPv6 range' => [ 'cidr', '2001:0DB8::/64', '2001:DB8:0:0:0:0:0:0/64' ],
			'Usemod IP' => [ 'ip', '192.168.0.xxx', '192.168.0.xxx' ],
			'Bogus IP' => [ '', '192.168.0.256', null ],
			'Bogus Usemod IP' => [ '', '192.268.0.xxx', null ],
			'Usemod IP as range' => [ '', '192.168.0.xxx/16', null ],
			'Bad username' => [ '', '[[Foo]]', null ],
			'No namespaces' => [ '', 'Talk:Foo', null ],
			'No namespaces (2)' => [ '', 'Help:Foo', null ],
			'Matches temporary user format but contains fragment' => [ '', '*Unregistered_123456#', null ],
			'No namespaces (except User is ok)' => [ 'name', 'User:Adam_Smith', 'Adam Smith' ],
			'No namespaces (except User is ok) (IPv6)' => [ 'ip', 'User:::1', '0:0:0:0:0:0:0:1' ],
			'No interwiki prefixes' => [ '', 'interwiki:Foo', null ],
			'No fragment in IP' => [ '', '192.168.0.256#', null ],
		];
		foreach ( $data as $key => [ $type, $input, $expect ] ) {
			$ex = new ValidationException(
				DataMessageValue::new( 'paramvalidator-baduser', [], 'baduser' ),
				'test', $input, []
			);
			if ( $type === '' ) {
				yield $key => [ $input, $ex ];
				continue;
			}

			yield $key => [ $input, $expect ];

			yield "$key, only '$type' allowed" => [
				$input,
				$expect,
				[ UserDef::PARAM_ALLOWED_USER_TYPES => [ $type ] ],
			];

			$types = array_diff( [ 'name', 'ip', 'temp', 'cidr', 'interwiki' ], [ $type ] );
			yield "$key, without '$type' allowed" => [
				$input,
				$ex,
				[ UserDef::PARAM_ALLOWED_USER_TYPES => $types ],
			];
			if ( $type === 'ip'
				|| $type === 'interwiki'
				|| $type === 'cidr'
			) {
				// For all of these the UserIdentity returned will be a
				// UserIdentityValue object since the name and id are both
				// known (id is 0 for all)
				$obj = UserIdentityValue::newAnonymous( $expect );
			} elseif ( $type === 'temp' ) {
				if ( $input === '*Unregistered 1' ) {
					$id = 100;
				} else {
					$id = 0;
				}
				$obj = new UserIdentityValue( $id, $expect );
			} else {
				// Creating from name, we are only testing for "Adam Smith"
				// so the id will be 1
				$obj = new UserIdentityValue( 1, $expect );
			}

			yield "$key, returning object" => [ $input, $obj, [ UserDef::PARAM_RETURN_OBJECT => true ] ];
		}

		// Test input by user ID
		// Since this user isn't in our mock UserIdentityLookup, the name is "Unknown user"
		// and the id is switched to just 0. We cover the case of existing ids in
		// testProcessUser()
		$input = '#1234';
		$ex = new ValidationException(
			DataMessageValue::new( 'paramvalidator-baduser', [], 'baduser' ),
			'test', $input, []
		);
		yield 'User ID' => [ $input, $ex, [ UserDef::PARAM_RETURN_OBJECT => true ] ];
		yield 'User ID, with \'id\' allowed, returning object' => [
			$input,
			new UserIdentityValue( 0, "Unknown user" ),
			[ UserDef::PARAM_ALLOWED_USER_TYPES => [ 'id' ], UserDef::PARAM_RETURN_OBJECT => true ],
		];

		// Tests for T232672 (consistent treatment of whitespace and BIDI characters)
		$data = [
			'name' => [ 'Emma', [ 1 ], 'Emma' ],
			'interwiki' => [ 'm>some_user', [ 1, 2, 6 ], null ],
			'ip (v4)' => [ '192.168.0.1', [ 1, 3, 4 ], '192.168.0.1' ],
			'ip (v6)' => [ '2001:DB8:0:0:0:0:0:0', [ 2, 5, 6 ], '2001:DB8:0:0:0:0:0:0' ],
			'ip (v6, colons)' => [ '::1', [ 1, 2 ], '0:0:0:0:0:0:0:1' ],
			'cidr (v4)' => [ '192.168.0.0/16', [ 1, 3, 4, 11, 12, 13 ], '192.168.0.0/16' ],
			'cidr (v6)' => [ '2001:db8::/64', [ 2, 5, 6, 20, 21, 22 ], '2001:DB8:0:0:0:0:0:0/64' ],
		];
		foreach ( $data as $key => [ $name, $positions, $expect ] ) {
			$input = " $name ";
			yield "T232672: leading/trailing whitespace for $key" => [ $input, $expect ?? $input ];

			$input = "_{$name}_";
			yield "T232672: leading/trailing underscores for $key" => [ $input, $expect ?? $input ];

			$positions = array_merge( [ 0, strlen( $name ) ], $positions );
			foreach ( $positions as $i ) {
				$input = substr_replace( $name, "\u{200E}", $i, 0 );
				yield "T232672: U+200E at position $i for $key" => [ $input, $expect ?? $input ];
			}
		}

		yield 'Not a string' => [
			[ 1, 2, 3 ],
			new ValidationException(
				DataMessageValue::new( 'paramvalidator-needstring', [], 'needstring' ),
				'test', '', []
			)
		];
	}

	public static function provideNormalizeSettings() {
		return [
			'Basic test' => [
				[ 'param-foo' => 'bar' ],
				[
					'param-foo' => 'bar',
					UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'cidr', 'interwiki' ],
				],
			],
			'Types not overridden' => [
				[
					'param-foo' => 'bar',
					UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'id' ],
				],
				[
					'param-foo' => 'bar',
					UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'id' ],
				],
			],
		];
	}

	public static function provideCheckSettings() {
		$keys = [ 'Y', UserDef::PARAM_ALLOWED_USER_TYPES, UserDef::PARAM_RETURN_OBJECT ];
		$ismultiIssue = 'Multi-valued user-type parameters with PARAM_RETURN_OBJECT or allowing IDs '
			. 'should set low values (<= 10) for PARAM_ISMULTI_LIMIT1 and PARAM_ISMULTI_LIMIT2.'
			. ' (Note that "<= 10" is arbitrary. If something hits this, we can investigate a real limit '
			. 'once we have a real use case to look at.)';

		return [
			'Basic test' => [
				[],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Test with everything' => [
				[
					UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name' ],
					UserDef::PARAM_RETURN_OBJECT => true,
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Bad types' => [
				[
					UserDef::PARAM_ALLOWED_USER_TYPES => 'name',
					UserDef::PARAM_RETURN_OBJECT => 1,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						UserDef::PARAM_RETURN_OBJECT => 'PARAM_RETURN_OBJECT must be boolean, got integer',
						UserDef::PARAM_ALLOWED_USER_TYPES => 'PARAM_ALLOWED_USER_TYPES must be an array, got string',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'PARAM_ALLOWED_USER_TYPES cannot be empty' => [
				[
					UserDef::PARAM_ALLOWED_USER_TYPES => [],
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						UserDef::PARAM_ALLOWED_USER_TYPES => 'PARAM_ALLOWED_USER_TYPES cannot be empty',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'PARAM_ALLOWED_USER_TYPES invalid values' => [
				[
					UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'id', 'ssn', 'Q-number' ],
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						UserDef::PARAM_ALLOWED_USER_TYPES
							=> 'PARAM_ALLOWED_USER_TYPES contains invalid values: ssn, Q-number',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'ISMULTI generally ok' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'ISMULTI with ID not ok (1)' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					UserDef::PARAM_ALLOWED_USER_TYPES => [ 'id' ],
				],
				self::STDRET,
				[
					'issues' => [ 'X', $ismultiIssue ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'ISMULTI with ID not ok (2)' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 10,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 11,
					UserDef::PARAM_ALLOWED_USER_TYPES => [ 'id' ],
				],
				self::STDRET,
				[
					'issues' => [ 'X', $ismultiIssue ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'ISMULTI with ID ok with low limits' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 10,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 10,
					UserDef::PARAM_ALLOWED_USER_TYPES => [ 'id' ],
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'ISMULTI with RETURN_OBJECT also not ok' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					UserDef::PARAM_RETURN_OBJECT => true,
				],
				self::STDRET,
				[
					'issues' => [ 'X', $ismultiIssue ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'ISMULTI with RETURN_OBJECT also ok with low limits' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 10,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 10,
					UserDef::PARAM_RETURN_OBJECT => true,
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
		];
	}

	public static function provideGetInfo() {
		return [
			'Basic test' => [
				[],
				[
					'subtypes' => [ 'name', 'ip', 'temp', 'cidr', 'interwiki' ],
				],
				[
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-user"><text>1</text><list listType="text"><text><message key="paramvalidator-help-type-user-subtype-name"></message></text><text><message key="paramvalidator-help-type-user-subtype-ip"></message></text><text><message key="paramvalidator-help-type-user-subtype-temp"></message></text><text><message key="paramvalidator-help-type-user-subtype-cidr"></message></text><text><message key="paramvalidator-help-type-user-subtype-interwiki"></message></text></list><num>5</num></message>',
				],
			],
			'Specific types' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'id' ],
					UserDef::PARAM_RETURN_OBJECT => true,
				],
				[
					'subtypes' => [ 'name', 'id' ],
				],
				[
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-user"><text>2</text><list listType="text"><text><message key="paramvalidator-help-type-user-subtype-name"></message></text><text><message key="paramvalidator-help-type-user-subtype-id"></message></text></list><num>2</num></message>',
				],
			],
		];
	}

	private function assertUserIdentity( $actual, $expectId, $expectName ) {
		// Can't use UserIdentity::equals() since that only checks the name
		$this->assertInstanceOf( UserIdentity::class, $actual );
		$this->assertSame( $expectId, $actual->getId() );
		$this->assertSame( $expectName, $actual->getName() );
	}

	/**
	 * @dataProvider provideMissingId
	 */
	public function testProcessUser_missingId( $missingId ) {
		// User created by id, does not exist, falls back to "Unknown user"
		// See our mock UserIdentityLookup for which ids and names exist
		$userDef = $this->getInstance( new SimpleCallbacks( [] ), [] );
		$res = $userDef->validate(
			'', // $name, unused here
			"#$missingId",
			[
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'id' ],
				UserDef::PARAM_RETURN_OBJECT => true,
			], // $settings
			[] // $options, unused here
		);
		// Even though we created with $missingId, the resulting UserIdentity has
		// an id of 0 because the user does not exist
		$this->assertUserIdentity( $res, 0, "Unknown user" );
	}

	public static function provideMissingId() {
		yield "0 no longer matches request ip" => [ 0 ];
		yield "Id with no user" => [ 6 ];
	}

	public function testProcessUser_validId() {
		// User created by id, does exist
		// See our mock UserIdentityLookup for which ids and names exist
		$userDef = $this->getInstance( new SimpleCallbacks( [] ), [] );
		$res = $userDef->validate(
			'', // $name, unused here
			"#5",
			[
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'id' ],
				UserDef::PARAM_RETURN_OBJECT => true,
			], // $settings
			[] // $options, unused here
		);
		$this->assertUserIdentity( $res, 5, 'Emma' );
	}

	public function testProcessUser_missingName() {
		// Created by name, does not exist
		// Already in the canonical form
		// See our mock UserIdentityLookup for which ids and names exist
		$userName = 'UserDefTest-processUser-missing';

		$userDef = $this->getInstance( new SimpleCallbacks( [] ), [] );
		$res = $userDef->validate(
			'', // $name, unused here
			$userName,
			[
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name' ],
				UserDef::PARAM_RETURN_OBJECT => true,
			], // $settings
			[] // $options, unused here
		);

		$this->assertUserIdentity( $res, 0, $userName );
	}

	public function testProcessUser_0() {
		$userName = '0';

		$userDef = $this->getInstance( new SimpleCallbacks( [] ), [] );
		$res = $userDef->validate(
			'', // $name, unused here
			$userName,
			[
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name' ],
				UserDef::PARAM_RETURN_OBJECT => true,
			], // $settings
			[] // $options, unused here
		);

		$this->assertUserIdentity( $res, 0, $userName );
	}

}
