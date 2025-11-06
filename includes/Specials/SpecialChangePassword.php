<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\SpecialPage\SpecialRedirectToSpecial;

/**
 * Compatibility and shortcut redirect to Special:ChangeCredentials,
 * and to hide internal AuthManager class names.
 *
 * @ingroup SpecialPage
 */
class SpecialChangePassword extends SpecialRedirectToSpecial {
	public function __construct() {
		parent::__construct( 'ChangePassword', 'ChangeCredentials',
			PasswordAuthenticationRequest::class, [ 'returnto', 'returntoquery' ] );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialChangePassword::class, 'SpecialChangePassword' );
