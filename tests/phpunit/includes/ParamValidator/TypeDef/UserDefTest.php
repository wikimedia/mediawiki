<?php

namespace MediaWiki\ParamValidator\TypeDef;

use User;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\TypeDefTestCase;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers MediaWiki\ParamValidator\TypeDef\UserDef
 */
class UserDefTest extends TypeDefTestCase {

	protected static $testClass = UserDef::class;

	private $wgHooks = null;

	protected function setUp(): void {
		global $wgHooks;

		parent::setUp();

		// We don't have MediaWikiIntegrationTestCase's methods available, so we have to do it ourself.
		$this->wgHooks = $wgHooks;
		$wgHooks['InterwikiLoadPrefix'][] = function ( $prefix, &$iwdata ) {
			if ( $prefix === 'interwiki' ) {
				$iwdata = [
					'iw_url' => 'http://example.com/',
					'iw_local' => 0,
					'iw_trans' => 0,
				];
				return false;
			}
		};
	}

	protected function tearDown(): void {
		global $wgHooks;

		$wgHooks = $this->wgHooks;

		parent::tearDown();
	}

	public function provideValidate() {
		// General tests of string inputs
		$data = [
			'Basic' => [ 'name', 'Some user', 'Some user' ],
			'Normalized' => [ 'name', 'some_user', 'Some user' ],
			'External' => [ 'interwiki', 'm>some_user', 'm>some_user' ],
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
			'No namespaces (except User is ok)' => [ 'name', 'User:some_user', 'Some user' ],
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

			$types = array_diff( [ 'name', 'ip', 'cidr', 'interwiki' ], [ $type ] );
			yield "$key, without '$type' allowed" => [
				$input,
				$ex,
				[ UserDef::PARAM_ALLOWED_USER_TYPES => $types ],
			];

			$obj = $type === 'name' ? User::newFromName( $expect ) : User::newFromAnyId( 0, $expect, null );
			yield "$key, returning object" => [ $input, $obj, [ UserDef::PARAM_RETURN_OBJECT => true ] ];
		}

		// Test input by user ID
		// We can't test not returning object here, because we don't have a test
		// database and there's no "UserFactory" (yet) to inject a mock of.
		$input = '#1234';
		$ex = new ValidationException(
			DataMessageValue::new( 'paramvalidator-baduser', [], 'baduser' ),
			'test', $input, []
		);
		yield 'User ID' => [ $input, $ex, [ UserDef::PARAM_RETURN_OBJECT => true ] ];
		yield 'User ID, with \'id\' allowed, returning object' => [
			$input,
			User::newFromId( 1234 ),
			[ UserDef::PARAM_ALLOWED_USER_TYPES => [ 'id' ], UserDef::PARAM_RETURN_OBJECT => true ],
		];

		// Tests for T232672 (consistent treatment of whitespace and BIDI characters)
		$data = [
			'name' => [ 'Foo', [ 1 ], 'Foo' ],
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
	}

	public function provideNormalizeSettings() {
		return [
			'Basic test' => [
				[ 'param-foo' => 'bar' ],
				[
					'param-foo' => 'bar',
					UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'cidr', 'interwiki' ],
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

	public function provideCheckSettings() {
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

	public function provideGetInfo() {
		return [
			'Basic test' => [
				[],
				[
					'subtypes' => [ 'name', 'ip', 'cidr', 'interwiki' ],
				],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-user"><text>1</text><list listType="text"><text><message key="paramvalidator-help-type-user-subtype-name"></message></text><text><message key="paramvalidator-help-type-user-subtype-ip"></message></text><text><message key="paramvalidator-help-type-user-subtype-cidr"></message></text><text><message key="paramvalidator-help-type-user-subtype-interwiki"></message></text></list><num>4</num></message>',
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
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-user"><text>2</text><list listType="text"><text><message key="paramvalidator-help-type-user-subtype-name"></message></text><text><message key="paramvalidator-help-type-user-subtype-id"></message></text></list><num>2</num></message>',
				],
			],
		];
	}

}
