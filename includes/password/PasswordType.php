<?php
/**
 * The basic PasswordType interface
 * Defines the methods that are required of a PasswordType class implementation
 */
interface PasswordType {

	/**
	 * Return the name of the PasswordType
	 * The password system will use this at the start of the data we store in the database
	 * eg: if getName returns 'A' the data will take the format ':A:...'.
	 *
	 * @return string The type name.
	 */
	public function getName();

	/**
	 * Create a hashed password to be stored in the database given a user's plaintext password.
	 *
	 * @param $password The plaintext password
	 */
	public function crypt( $password );

	/**
	 * Compare the hashed db form of a password with a plaintext password to see if the
	 * password is correct.
	 *
	 * @param $data string The password data. Same format as outputted by crypt()
	 * @param $password The plaintext password
	 * @return Status A Status object;
	 *         - Good with a value of true for a password match
	 *         - Good with a value of false for a bad password
	 *         - Fatal if the password data was badly formed or there was some issue with
	 *           comparing the passwords which is not the user's fault.
	 */
	public function compare( $data, $password );

	/**
	 * Check and see if the hashed data of a password is in preferred format.
	 * For example if you use a variable hash type and let site config specify what hash
	 * function to use this could return false if the params in $data does not use
	 * the hash that was configured.
	 *
	 * When this method returns false the User's password may be 'upgraded' by calling
	 * crypt() again to generate a new hash for the password.
	 *
	 * @param $data string The password data. Same format as outputted by crypt()
	 * @return bool
	 */
	public function isPreferredFormat( $data );

}
