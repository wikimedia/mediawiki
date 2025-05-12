<?php

use MediaWiki\Password\BcryptPassword;

/**
 * @group large
 * @covers \MediaWiki\Password\BcryptPassword
 * @covers \MediaWiki\Password\ParameterizedPassword
 * @covers \MediaWiki\Password\Password
 * @covers \MediaWiki\Password\PasswordFactory
 */
class BcryptPasswordTest extends PasswordTestCase {
	protected static function getTypeConfigs() {
		return [ 'bcrypt' => [
			'class' => BcryptPassword::class,
			'cost' => 4,
		] ];
	}

	public static function providePasswordTests() {
		return [
			// Tests from glibc bcrypt implementation
			[ true, ':bcrypt:5$CCCCCCCCCCCCCCCCCCCCC.$E5YPO9kmyuRGyh0XouQYb4YMJKvyOeW', "U*U" ],
			[ true, ':bcrypt:5$CCCCCCCCCCCCCCCCCCCCC.$VGOzA784oUp/Z0DY336zx7pLYAy0lwK', "U*U*" ],
			[ true, ':bcrypt:5$XXXXXXXXXXXXXXXXXXXXXO$AcXxm9kjPGEMsLznoKqmqw7tc8WCx4a', "U*U*U" ],
			[ true, ':bcrypt:5$abcdefghijklmnopqrstuu$5s2v8.iXieOjg/.AySBTTZIIVFJeBui', "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789chars after 72 are ignored" ],
			[ true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$CE5elHaaO4EbggVDjb8P19RukzXSM3e', "\xff\xff\xa3" ],
			[ true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$Sa7shbm4.OzKpvFnX1pQLmQW96oUlCq', "\xa3" ],
			[ true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$Sa7shbm4.OzKpvFnX1pQLmQW96oUlCq', "\xa3" ],
			[ true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$o./n25XVfn6oAPaUvHe.Csk4zRfsYPi', "\xff\xa334\xff\xff\xff\xa3345" ],
			[ true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$nRht2l/HRhr6zmCp9vYUvvsqynflf9e', "\xff\xa3345" ],
			[ true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$nRht2l/HRhr6zmCp9vYUvvsqynflf9e', "\xff\xa3345" ],
			[ true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$6IflQkJytoRVc1yuaNtHfiuq.FRlSIS', "\xa3ab" ],
			[ true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$6IflQkJytoRVc1yuaNtHfiuq.FRlSIS', "\xa3ab" ],
			[ true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$swQOIzjOiJ9GHEPuhEkvqrUyvWhEMx6', "\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaachars after 72 are ignored as usual" ],
			[ true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$R9xrDjiycxMbQE2bp.vgqlYpW5wx2yy', "\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55" ],
			[ true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$9tQZzcJfm3uj2NvJ/n5xkhpqLrMpWCe', "\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff" ],
			[ true, ':bcrypt:5$CCCCCCCCCCCCCCCCCCCCC.$7uG0VCzI2bS7j6ymqJi9CdcdxiRTWNy', "" ],
			// False positive tests
			[ false, ':bcrypt:5$CCCCCCCCCCCCCCCCCCCCC.$E5YPO9kmyuRGyh0XouQYb4YMJKvyOeW', "UXU" ],
			[ false, ':bcrypt:5$CCCCCCCCCCCCCCCCCCCCC.$E5YPO9kmyuRGyh0XouQYb4YMJKvyOeW', "" ],
		];
		// phpcs:enable
	}
}
