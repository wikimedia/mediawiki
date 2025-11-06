<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use LogicException;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\User\UserFactory;
use Profiler;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\ScopedCallback;

/**
 * Cancel an email confirmation using the e-mail confirmation code.
 *
 * @see SpecialConfirmEmail
 * @ingroup SpecialPage
 * @ingroup Auth
 */
class SpecialEmailInvalidate extends UnlistedSpecialPage {

	private UserFactory $userFactory;

	public function __construct( UserFactory $userFactory ) {
		parent::__construct( 'Invalidateemail', 'editmyprivateinfo' );

		$this->userFactory = $userFactory;
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/** @inheritDoc */
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
			IDBAccessObject::READ_LATEST
		);

		if ( !is_object( $user ) ) {
			$this->getOutput()->addWikiMsg( 'confirmemail_invalid' );

			return;
		}

		$userLatest = $user->getInstanceFromPrimary() ?? throw new LogicException( 'No user' );
		$userLatest->invalidateEmail();
		$userLatest->saveSettings();
		$this->getOutput()->addWikiMsg( 'confirmemail_invalidated' );

		if ( !$this->getUser()->isRegistered() ) {
			$this->getOutput()->returnToMain();
		}
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialEmailInvalidate::class, 'SpecialEmailInvalidate' );
