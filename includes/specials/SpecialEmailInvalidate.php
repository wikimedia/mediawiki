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

use MediaWiki\User\UserFactory;
use Wikimedia\ScopedCallback;

/**
 * Special page allows users to cancel an email confirmation using the e-mail
 * confirmation code
 *
 * @ingroup SpecialPage
 */
class SpecialEmailInvalidate extends UnlistedSpecialPage {

	/** @var UserFactory */
	private $userFactory;

	/**
	 * @param UserFactory $userFactory
	 */
	public function __construct( UserFactory $userFactory ) {
		parent::__construct( 'Invalidateemail', 'editmyprivateinfo' );

		$this->userFactory = $userFactory;
	}

	public function doesWrites() {
		return true;
	}

	public function execute( $code ) {
		// Ignore things like primary queries/connections on GET requests.
		// It's very convenient to just allow formless link usage.
		$trxProfiler = Profiler::instance()->getTransactionProfiler();

		$this->setHeaders();
		$this->checkReadOnly();
		$this->checkPermissions();

		$scope = $trxProfiler->silenceForScope( $trxProfiler::EXPECTATION_REPLICAS_ONLY );
		$this->attemptInvalidate( $code );
		ScopedCallback::consume( $scope );
	}

	/**
	 * Attempt to invalidate the user's email address and show success or failure
	 * as needed; if successful, link to main page
	 *
	 * @param string $code Confirmation code
	 */
	private function attemptInvalidate( $code ) {
		$user = $this->userFactory->newFromConfirmationCode(
			(string)$code,
			UserFactory::READ_LATEST
		);

		if ( !is_object( $user ) ) {
			$this->getOutput()->addWikiMsg( 'confirmemail_invalid' );

			return;
		}

		$user->invalidateEmail();
		$user->saveSettings();
		$this->getOutput()->addWikiMsg( 'confirmemail_invalidated' );

		if ( !$this->getUser()->isRegistered() ) {
			$this->getOutput()->returnToMain();
		}
	}
}
