<?php
/**
 * Implements Special:GoToInterwiki
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
 * Landing page for non-local interwiki links.
 *
 * Meant to warn people that the site they're visiting
 * is not the local wiki (In case of phishing tricks).
 * Only meant to be used for things that directly
 * redirect from url (e.g. Special:Search/google:foo )
 * Not meant for general interwiki linking (e.g.
 * [[google:foo]] should still directly link)
 *
 * @ingroup SpecialPage
 */
class SpecialGoToInterwiki extends UnlistedSpecialPage {
	public function __construct( $name = 'GoToInterwiki' ) {
		parent::__construct( $name );
	}

	public function execute( $par ) {
		// Allow forcing an interstitial for local interwikis. This is used
		// when a redirect page is reached via a special page which resolves
		// to a user-dependent value (as defined by
		// RedirectSpecialPage::personallyIdentifiableTarget). See the hack
		// for avoiding T109724 in MediaWiki::performRequest (which also
		// explains why we can't use a query parameter instead).
		//
		// HHVM dies when substr_compare is used on an empty string so ensure it's not.
		$force = ( substr_compare( $par ?: 'x', 'force/', 0, 6 ) === 0 );
		if ( $force ) {
			$par = substr( $par, 6 );
		}

		$this->setHeaders();
		$target = Title::newFromText( $par );
		// Disallow special pages as a precaution against
		// possible redirect loops.
		if ( !$target || $target->isSpecialPage() ) {
			$this->getOutput()->setStatusCode( 404 );
			$this->getOutput()->addWikiMsg( 'gotointerwiki-invalid' );
			return;
		}

		$url = $target->getFullURL();
		if ( !$target->isExternal() || ( $target->isLocal() && !$force ) ) {
			// Either a normal page, or a local interwiki.
			// Just redirect.
			$this->getOutput()->redirect( $url, '301' );
		} else {
			$this->getOutput()->addWikiMsg(
				'gotointerwiki-external',
				$url,
				$target->getFullText()
			);
		}
	}

	/**
	 * @return bool
	 */
	public function requiresWrite() {
		return false;
	}

	/**
	 * @return string
	 */
	protected function getGroupName() {
		return 'redirects';
	}
}
