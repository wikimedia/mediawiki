<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\Specials\Redirects;

use MediaWiki\SpecialPage\RedirectSpecialArticle;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserConfig;

/**
 * Redirect to the current user's user page.
 *
 * @ingroup SpecialPage
 */
class SpecialMypage extends RedirectSpecialArticle {

	private TempUserConfig $tempUserConfig;

	public function __construct( TempUserConfig $tempUserConfig ) {
		parent::__construct( 'Mypage' );

		$this->tempUserConfig = $tempUserConfig;
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

		if ( $subpage === null || $subpage === '' ) {
			return Title::makeTitle( NS_USER, $this->getUser()->getName() );
		}

		return Title::makeTitle( NS_USER, $this->getUser()->getName() . '/' . $subpage );
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
class_alias( SpecialMypage::class, 'SpecialMypage' );
