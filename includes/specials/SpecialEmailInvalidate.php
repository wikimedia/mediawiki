<?php
/**
 * Implements Special:EmailInvalidation
 *
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
 * @ingroup SpecialPage
 */

/**
 * Special page allows users to cancel an email confirmation using the e-mail
 * confirmation code
 *
 * @ingroup SpecialPage
 */
class SpecialEmailInvalidate extends UnlistedSpecialPage {
	public function __construct() {
		parent::__construct( 'Invalidateemail', 'editmyprivateinfo' );
	}

	public function doesWrites() {
		return true;
	}

	function execute( $code ) {
		// Ignore things like master queries/connections on GET requests.
		// It's very convenient to just allow formless link usage.
		$trxProfiler = Profiler::instance()->getTransactionProfiler();

		$this->setHeaders();
		$this->checkReadOnly();
		$this->checkPermissions();

		$old = $trxProfiler->setSilenced( true );
		$this->attemptInvalidate( $code );
		$trxProfiler->setSilenced( $old );
	}

	/**
	 * Attempt to invalidate the user's email address and show success or failure
	 * as needed; if successful, link to main page
	 *
	 * @param string $code Confirmation code
	 */
	private function attemptInvalidate( $code ) {
		$user = User::newFromConfirmationCode( $code, User::READ_LATEST );
		if ( !is_object( $user ) ) {
			$this->getOutput()->addWikiMsg( 'confirmemail_invalid' );

			return;
		}

		$user->invalidateEmail();
		$user->saveSettings();
		$this->getOutput()->addWikiMsg( 'confirmemail_invalidated' );

		if ( !$this->getUser()->isLoggedIn() ) {
			$this->getOutput()->returnToMain();
		}
	}
}
