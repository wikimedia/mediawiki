<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Auth;

use MediaWiki\Session\SessionManager;
use MediaWiki\Session\SessionProvider;
use UnexpectedValueException;

/**
 * This is an authentication request added by AuthManager to show a "remember me" checkbox.
 *
 * When checked, it will take more time for the authenticated session to expire.
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
class RememberMeAuthenticationRequest extends AuthenticationRequest {

	/**
	 * Indicates that the user may be able to choose whether to be remembered or not. The
	 * choice field is skippable if no other fields are on the login form. This is the original
	 * pre-1.36 behavior.
	 */
	public const CHOOSE_REMEMBER = 'choose';

	/**
	 * Indicates that the user will be able to choose whether to be remembered or not. The
	 * choice field is not skippable, even if there are no other fields on the login form.
	 */
	public const FORCE_CHOOSE_REMEMBER = 'force-choose';

	/**
	 * Indicates that the user will always be remembered.
	 */
	public const ALWAYS_REMEMBER = 'always';

	/**
	 * Indicates that the user will never be remembered.
	 */
	public const NEVER_REMEMBER = 'never';

	/**
	 * Allowed configuration flags
	 */
	public const ALLOWED_FLAGS = [
		self::CHOOSE_REMEMBER,
		self::FORCE_CHOOSE_REMEMBER,
		self::ALWAYS_REMEMBER,
		self::NEVER_REMEMBER,
	];

	/**
	 * Whether this field must be filled in on the form. Since the field is a checkbox, which can
	 * by definition be left blank, it is always optional.
	 * @var bool
	 */
	public $required = self::OPTIONAL;

	/**
	 * Whether display of this field can be skipped, accepting the default value, if there are no
	 * other fields on the form.
	 * @var bool
	 * @since 1.36
	 */
	public $skippable = true;

	/** @var bool */
	private $checkbox = false;

	/**
	 * @var int|null How long the user will be remembered, in seconds.
	 * Null means setting the $rememberMe will have no effect
	 */
	protected $expiration = null;

	/** @var bool */
	public $rememberMe = false;

	/**
	 * @stable to call
	 * @param string $flag
	 */
	public function __construct( string $flag = self::CHOOSE_REMEMBER ) {
		/** @var SessionProvider $provider */
		$provider = SessionManager::getGlobalSession()->getProvider();
		'@phan-var SessionProvider $provider';

		switch ( $flag ) {
			case self::CHOOSE_REMEMBER:
				$this->skippable = true;
				$this->checkbox = true;
				$this->expiration = $provider->getRememberUserDuration();
				break;
			case self::FORCE_CHOOSE_REMEMBER:
				$this->skippable = false;
				$this->checkbox = true;
				$this->expiration = $provider->getRememberUserDuration();
				break;
			case self::ALWAYS_REMEMBER:
				$this->skippable = true;
				$this->checkbox = false;
				$this->expiration = $provider->getRememberUserDuration();
				break;
			case self::NEVER_REMEMBER:
				$this->skippable = true;
				$this->checkbox = false;
				$this->expiration = null;
				break;
			default:
				throw new UnexpectedValueException( '$flag must be one of the values: \'' .
					implode( "', '", self::ALLOWED_FLAGS ) . '\'' );
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getFieldInfo() {
		if ( !$this->expiration || !$this->checkbox ) {
			return [];
		}

		$expirationDays = ceil( $this->expiration / ( 3600 * 24 ) );
		return [
			'rememberMe' => [
				'type' => 'checkbox',
				'label' => wfMessage( 'userlogin-remembermypassword' )->numParams( $expirationDays ),
				'help' => wfMessage( 'authmanager-userlogin-remembermypassword-help' ),
				'optional' => true,
				'skippable' => $this->skippable,
			]
		];
	}
}
