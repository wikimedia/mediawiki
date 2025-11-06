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
 * Redirect to Special:Listfiles for the current user's name or IP.
 *
 * @ingroup SpecialPage
 */
class SpecialMyuploads extends RedirectSpecialPage {

	private TempUserConfig $tempUserConfig;

	public function __construct( TempUserConfig $tempUserConfig ) {
		parent::__construct( 'Myuploads' );

		$this->tempUserConfig = $tempUserConfig;
		$this->mAllowedRedirectParams = [ 'limit', 'ilshowall', 'ilsearch' ];
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
class_alias( SpecialMyuploads::class, 'SpecialMyuploads' );
