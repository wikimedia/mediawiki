<?php
/**
 * An old non-salted password type.
 * Simply md5s the password.
 */
class Password_TypeA extends BasePasswordType {

	protected function run( $params, $password ) {
		self::params( $params, 0 );
		return md5( $password );
	}

	protected function cryptParams() {
		return array();
	}

}

/**
 * Our first salted password type.
 * md5s a combination of a 32bit salt a '-' separator and
 * the md5 of the password.
 */
class Password_TypeB extends BasePasswordType {

	protected function run( $params, $password ) {
		list( $salt ) = self::params( $params, 1 );
		return md5( $salt . '-' . md5( $password ) );
	}

	protected function cryptParams() {
		$salt = MWCryptRand::generateHex( 8 );
		return array( $salt );
	}

}
