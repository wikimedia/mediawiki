<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials\Redirects;

use MediaWiki\SpecialPage\RedirectSpecialPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\TempUser\TempUserConfig;

/**
 * Redirect to current user's uploaded files, including old versions.
 *
 * @see SpecialMyuploads
 * @ingroup SpecialPage
 */
class SpecialAllMyUploads extends RedirectSpecialPage {

	private TempUserConfig $tempUserConfig;

	public function __construct( TempUserConfig $tempUserConfig ) {
		parent::__construct( 'AllMyUploads' );

		$this->tempUserConfig = $tempUserConfig;

		$this->mAllowedRedirectParams = [ 'limit', 'ilsearch' ];
	}

	/** @inheritDoc */
	public function execute( $subpage ) {
		// Redirect to login for anon users when temp accounts are enabled.
		if ( $this->tempUserConfig->isEnabled() && $this->getUser()->isAnon() ) {
			$this->requireLogin();
		}
		parent::execute( $subpage );
	}

	/** @inheritDoc */
	public function getRedirect( $subpage ) {
		if ( $this->tempUserConfig->isEnabled() && $this->getUser()->isAnon() ) {
			return false;
		}

		$this->mAddedRedirectParams['ilshowall'] = 1;
		return SpecialPage::getTitleFor( 'Listfiles', $this->getUser()->getName() );
	}

	/**
	 * Target identifies a specific User. See T109724.
	 *
	 * @since 1.27
	 * @return bool
	 */
	public function personallyIdentifiableTarget() {
		return true;
	}
}
/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialAllMyUploads::class, 'SpecialAllMyUploads' );
