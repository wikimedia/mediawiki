<?php

use PHPUnit\Framework\TestCase;
use Wikimedia\Http\SetCookieCompat;

/**
 * @covers SetCookieCompat
 */
class SetCookieCompatTest extends TestCase {
	public static function provideSetCookieEmulated() {
		// Expected values are all copied from PHP 7.4
		// phpcs:disable Generic.Files.LineLength.TooLong
		return [
			'unrecognised key' => [
				true, 'a', '',
				[
					'path' => '/',
					'foo' => 'bar'
				],
				[
					'headers' => [
						'Set-Cookie: a=deleted; expires=Thu, 01-Jan-1970 00:00:01 GMT; Max-Age=0; path=/',
					],
					'returnValue' => true,
					'errors' => [
						'setcookie(): Unrecognized key \'foo\' found in the options array',
					],
				],
			],
			'no valid options' => [
				true, 'a', '',
				[
					'foo' => 'bar'
				],
				[
					'headers' => [
						'Set-Cookie: a=deleted; expires=Thu, 01-Jan-1970 00:00:01 GMT; Max-Age=0',
					],
					'returnValue' => true,
					'errors' => [
						'setcookie(): Unrecognized key \'foo\' found in the options array',
						'setcookie(): No valid options were found in the given array',
					],
			]
			],
			'empty name' => [
				true, '', '', [],
				[
					'headers' => [],
					'returnValue' => false,
					'errors' => [
						'Cookie names must not be empty',
					],
				],
			],
			'invalid name' => [
				true, "\n", '', [],
				[
					'headers' => [],
					'returnValue' => false,
					'errors' => [
						'Cookie names cannot contain any of the following \'=,; \\t\\r\\n\\013\\014\'',
					],
				]
			],
			'invalid value' => [
				false, 'a', "\n", [],
				[
					'headers' => [],
					'returnValue' => false,
					'errors' => [
						'Cookie values cannot contain any of the following \',; \\t\\r\\n\\013\\014\'',
					],
				]
			],
			'escaped invalid value' => [
				true, 'a', "\n", [],
				[
					'headers' => [
						'Set-Cookie: a=%0A',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'invalid path' => [
				true, 'a', 'b', [ 'path' => "\n" ],
				[
					'headers' => [],
					'returnValue' => false,
					'errors' => [
						'Cookie paths cannot contain any of the following \',; \\t\\r\\n\\013\\014\'',
					],
				]
			],
			'invalid domain' => [
				true, 'a', 'b', [ 'domain' => "\013" ],
				[
					'headers' => [],
					'returnValue' => false,
					'errors' => [
						'Cookie domains cannot contain any of the following \',; \\t\\r\\n\\013\\014\'',
					],
				]
			],
			'empty value' => [
				true, 'a', '', [],
				[
					'headers' => [
						'Set-Cookie: a=deleted; expires=Thu, 01-Jan-1970 00:00:01 GMT; Max-Age=0',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'encoded value' => [
				true, 'a', '%', [],
				[
					'headers' => [
						'Set-Cookie: a=%25',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'encoded value with space' => [
				true, 'a', 'b c', [],
				[
					'headers' => [
						'Set-Cookie: a=b%20c',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'raw value' => [
				false, 'a', '%', [],
				[
					'headers' => [
						'Set-Cookie: a=%',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'expires too large' => [
				true, 'a', 'b', [ 'expires' => 253430725200 ],
				[
					'headers' => [],
					'returnValue' => false,
					'errors' => [
						'Expiry date cannot have a year greater than 9999',
					],
				]
			],
			'expires in the past' => [
				true, 'a', 'b', [ 'expires' => 979477200 ],
				[
					'headers' => [
						'Set-Cookie: a=b; expires=Sun, 14-Jan-2001 13:00:00 GMT; Max-Age=0',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'expires in the future' => [
				true, 'a', 'b', [ 'expires' => 32504889600 ],
				[
					'headers' => [
						'Set-Cookie: a=b; expires=Wed, 15-Jan-3000 00:00:00 GMT; Max-Age=30911234470',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'path' => [
				false, 'a', 'b', [ 'path' => '%%' ],
				[
					'headers' => [
						'Set-Cookie: a=b; path=%%',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'domain' => [
				false, 'a', 'b', [ 'domain' => '%%' ],
				[
					'headers' => [
						'Set-Cookie: a=b; domain=%%',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'secure' => [
				false, 'a', 'b', [ 'secure' => true ],
				[
					'headers' => [
						'Set-Cookie: a=b; secure',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'httponly' => [
				false, 'a', 'b', [ 'httponly' => true ],
				[
					'headers' => [
						'Set-Cookie: a=b; HttpOnly',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'samesite' => [
				false, 'a', 'b', [ 'samesite' => 'None' ],
				[
					'headers' => [
						'Set-Cookie: a=b; SameSite=None',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],
			'multiple options' => [
				false, 'a', 'b', [
					'expires' => 32504889600,
					'path' => '/%',
					'domain' => '%.com',
					'secure' => true,
					'httponly' => true
				],
				[
					'headers' => [
						'Set-Cookie: a=b; expires=Wed, 15-Jan-3000 00:00:00 GMT; Max-Age=30911234470; path=/%; domain=%.com; secure; HttpOnly',
					],
					'returnValue' => true,
					'errors' => [],
				]
			],

		];
	}

	/**
	 * @dataProvider provideSetCookieEmulated
	 */
	public function testSetCookieEmulated( $urlEncode, $name, $value, $options, $expected ) {
		$instance = new class extends SetCookieCompat {
			public $headers = [];
			public $errors = [];

			protected function time() {
				return 1593655130;
			}

			protected function error( $error ) {
				$this->errors[] = $error;
			}

			protected function headers_sent() {
				return false;
			}

			protected function header( $header ) {
				$this->headers[] = $header;
			}
		};

		$ret = $instance->setCookieEmulated( $urlEncode, $name, $value, $options );
		$this->assertSame( $expected['headers'], $instance->headers );
		$this->assertSame( $expected['errors'], $instance->errors );
		$this->assertSame( $expected['returnValue'], $ret );
	}
}
