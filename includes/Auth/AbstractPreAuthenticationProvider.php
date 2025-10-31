<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Auth;

/**
 * A base class that implements some of the boilerplate for a PreAuthenticationProvider
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
abstract class AbstractPreAuthenticationProvider extends AbstractAuthenticationProvider
	implements PreAuthenticationProvider
{

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getAuthenticationRequests( $action, array $options ) {
		return [];
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function testForAuthentication( array $reqs ) {
		return \StatusValue::newGood();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function postAuthentication( $user, AuthenticationResponse $response ) {
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return \StatusValue::newGood();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function testUserForCreation( $user, $autocreate, array $options = [] ) {
		return \StatusValue::newGood();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function postAccountCreation( $user, $creator, AuthenticationResponse $response ) {
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function testForAccountLink( $user ) {
		return \StatusValue::newGood();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function postAccountLink( $user, AuthenticationResponse $response ) {
	}

}
