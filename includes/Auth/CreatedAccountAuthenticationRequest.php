<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Auth;

/**
 * Returned from account creation to allow for logging into the created account
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
class CreatedAccountAuthenticationRequest extends AuthenticationRequest {

	/** @inheritDoc */
	public $required = self::OPTIONAL;

	/** @var int User id */
	public $id;

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getFieldInfo() {
		return [];
	}

	/**
	 * @stable to call
	 * @param int $id User id
	 * @param string $name Username
	 */
	public function __construct( $id, $name ) {
		$this->id = (int)$id;
		$this->username = $name;
	}
}
