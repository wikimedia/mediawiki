<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials\Redirects;

use MediaWiki\Logging\LogPage;
use MediaWiki\SpecialPage\RedirectSpecialPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\TempUser\TempUserConfig;

/**
 * Redirect to Special:Log for the current user's name or IP.
 *
 * @ingroup SpecialPage
 */
class SpecialMylog extends RedirectSpecialPage {

	private TempUserConfig $tempUserConfig;

	public function __construct( TempUserConfig $tempUserConfig ) {
		parent::__construct( 'Mylog' );

		$this->tempUserConfig = $tempUserConfig;

		$this->mAllowedRedirectParams = [ 'type', 'subtype', 'page', 'pattern',
			'tagfilter', 'tagInvert', 'offset', 'dir', 'offender',
			'year', 'month', 'day' ];
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
			return SpecialPage::getSafeTitleFor( 'Log', $this->getUser()->getName() );
		}

		return SpecialPage::getSafeTitleFor( 'Log', $subpage . '/' . $this->getUser()->getName() );
	}

	/**
	 * Target identifies a specific User. See T109724.
	 *
	 * @return bool
	 */
	public function personallyIdentifiableTarget() {
		return true;
	}

	/**
	 * Return an array of subpages that this special page will accept.
	 *
	 * @return string[] subpages
	 */
	public function getSubpagesForPrefixSearch() {
		$subpages = LogPage::validTypes();
		$subpages[] = 'all';
		sort( $subpages );
		return $subpages;
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialMylog::class, 'SpecialMylog' );
