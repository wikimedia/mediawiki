<?php

/**
 * @group large
 */
class BcryptPasswordTestCase extends PasswordTestCase {
	protected function getTypeConfigs() {
		return array( 'bcrypt' => array(
			'class' => 'BcryptPassword',
			'cost' => 9,
		) );
	}

	public static function providePasswordTests() {
		/** @codingStandardsIgnoreStart Generic.Files.LineLength.TooLong */
		return array(
			// Tests from glibc bcrypt implementation
			array( true, ':bcrypt:5$CCCCCCCCCCCCCCCCCCCCC.$E5YPO9kmyuRGyh0XouQYb4YMJKvyOeW', "U*U" ),
			array( true, ':bcrypt:5$CCCCCCCCCCCCCCCCCCCCC.$VGOzA784oUp/Z0DY336zx7pLYAy0lwK', "U*U*" ),
			array( true, ':bcrypt:5$XXXXXXXXXXXXXXXXXXXXXO$AcXxm9kjPGEMsLznoKqmqw7tc8WCx4a', "U*U*U" ),
			array( true, ':bcrypt:5$abcdefghijklmnopqrstuu$5s2v8.iXieOjg/.AySBTTZIIVFJeBui', "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789chars after 72 are ignored" ),
			array( true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$CE5elHaaO4EbggVDjb8P19RukzXSM3e', "\xff\xff\xa3" ),
			array( true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$Sa7shbm4.OzKpvFnX1pQLmQW96oUlCq', "\xa3" ),
			array( true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$Sa7shbm4.OzKpvFnX1pQLmQW96oUlCq', "\xa3" ),
			array( true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$o./n25XVfn6oAPaUvHe.Csk4zRfsYPi', "\xff\xa334\xff\xff\xff\xa3345" ),
			array( true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$nRht2l/HRhr6zmCp9vYUvvsqynflf9e', "\xff\xa3345" ),
			array( true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$nRht2l/HRhr6zmCp9vYUvvsqynflf9e', "\xff\xa3345" ),
			array( true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$6IflQkJytoRVc1yuaNtHfiuq.FRlSIS', "\xa3ab" ),
			array( true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$6IflQkJytoRVc1yuaNtHfiuq.FRlSIS', "\xa3ab" ),
			array( true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$swQOIzjOiJ9GHEPuhEkvqrUyvWhEMx6', "\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaa\xaachars after 72 are ignored as usual" ),
			array( true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$R9xrDjiycxMbQE2bp.vgqlYpW5wx2yy', "\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55\xaa\x55" ),
			array( true, ':bcrypt:5$/OK.fbVrR/bpIqNJ5ianF.$9tQZzcJfm3uj2NvJ/n5xkhpqLrMpWCe', "\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff\x55\xaa\xff" ),
			array( true, ':bcrypt:5$CCCCCCCCCCCCCCCCCCCCC.$7uG0VCzI2bS7j6ymqJi9CdcdxiRTWNy', "" ),
			// One or two false sanity tests
			array( false, ':bcrypt:5$CCCCCCCCCCCCCCCCCCCCC.$E5YPO9kmyuRGyh0XouQYb4YMJKvyOeW', "UXU" ),
			array( false, ':bcrypt:5$CCCCCCCCCCCCCCCCCCCCC.$E5YPO9kmyuRGyh0XouQYb4YMJKvyOeW', "" ),
		);
		/** @codingStandardsIgnoreEnd */
	}
}
