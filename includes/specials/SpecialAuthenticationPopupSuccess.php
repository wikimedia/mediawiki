<?php
/**
 * Implements Special:AuthenticationPopupSuccess
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

namespace MediaWiki\Specials;

use MediaWiki\Skin\SkinFactory;
use MediaWiki\SpecialPage\UnlistedSpecialPage;

/**
 * A page for the 'mediawiki.authenticationPopup' module. If opened in a popup window,
 * it communicates with the module on the parent page and closes itself.
 *
 * @ingroup SpecialPage
 */
class SpecialAuthenticationPopupSuccess extends UnlistedSpecialPage {
	private SkinFactory $skinFactory;

	public function __construct(
		SkinFactory $skinFactory
	) {
		parent::__construct( 'AuthenticationPopupSuccess' );
		$this->skinFactory = $skinFactory;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		if ( $this->getRequest()->getRawVal( 'display' ) === 'popup' ) {
			// Replace the default skin with a "micro-skin" that omits most of the interface. (T362706)
			// In the future, we might allow normal skins to serve this mode too, if they advise that
			// they support it by setting a skin option, so that colors and fonts could stay consistent.
			$this->getContext()->setSkin( $this->skinFactory->makeSkin( 'authentication-popup' ) );
		}

		$this->setHeaders();

		$out = $this->getOutput();
		$out->addModules( 'mediawiki.authenticationPopup.success' );

		if ( $this->getUser()->isNamed() ) {
			$out->setPageTitleMsg( $this->msg( 'loginsuccesstitle' ) );
			$out->addWikiMsg( 'loginsuccess', wfEscapeWikiText( $this->getUser()->getName() ) );
		} else {
			$out->setPageTitleMsg( $this->msg( 'exception-nologin' ) );
			$out->addWikiMsg( 'exception-nologin-text' );
		}
		$out->addWikiMsg( 'userlogin-authpopup-closeme' );
	}
}
