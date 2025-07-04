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

namespace MediaWiki\Specials;

use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Title\Title;

/**
 * Landing page for non-local interwiki links.
 *
 * This exists for security and privacy reasons.
 *
 * The landing page warns people and obtains consent before leaving
 * the site and visiting a third-party website. This can reduce
 * impact of phishing tricks as well.
 *
 * This is meant to be used as the replacement URL when resolving
 * an interwiki link things in a context where it would be
 * navigated to without clear consent. For example, when doing
 * a simple search (not "advanced") in which we would normally
 * redirect to the first result if there is an exact match
 * (e.g. Special:Search/google:foo).
 *
 * This is not needed for external interwiki links in content,
 * e.g. [[google:foo]] in parser output may link directly.
 *
 * Further context at https://phabricator.wikimedia.org/T122209.
 *
 * @ingroup SpecialPage
 */
class SpecialGoToInterwiki extends UnlistedSpecialPage {
	public function __construct() {
		parent::__construct( 'GoToInterwiki' );
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$par ??= '';

		// Allow forcing an interstitial for local interwikis. This is used
		// when a redirect page is reached via a special page which resolves
		// to a user-dependent value (as defined by
		// RedirectSpecialPage::personallyIdentifiableTarget). See the hack
		// for avoiding T109724 in MediaWiki::performRequest (which also
		// explains why we can't use a query parameter instead).
		$force = str_starts_with( $par, 'force/' );
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

/** @deprecated class alias since 1.41 */
class_alias( SpecialGoToInterwiki::class, 'SpecialGoToInterwiki' );
