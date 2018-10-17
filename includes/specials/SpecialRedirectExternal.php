<?php

/**
 * Implements Special:RedirectExternal.
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
 * An unlisted special page that accepts a URL as the first argument, and redirects the user to
 * that page. Example: Special:Redirect/https://mediawiki.org
 *
 * At the moment, this is intended to be used by the GrowthExperiments project in order
 * to track outbound visits to certain external links. But it could be extended in the future to
 * provide parameters for showing a message to the user before redirecting, or explicitly requiring
 * a user to click on the link. This can help improve security when users follow on-wiki links to
 * off-wiki sites.
 */
class SpecialRedirectExternal extends UnlistedSpecialPage {

	public function __construct() {
		parent::__construct( 'RedirectExternal' );
	}

	/**
	 * @param string $url
	 * @return bool
	 * @throws HttpError
	 */
	public function execute( $url = '' ) {
		$dispatch = $this->dispatch( $url );
		if ( $dispatch->getStatusValue()->isGood() ) {
			$this->getOutput()->redirect( $url );
			return true;
		}
		throw new HttpError( 400, $dispatch->getMessage() );
	}

	/**
	 * @param string $url
	 * @return Status
	 */
	public function dispatch( $url ) {
		if ( !$url ) {
			return Status::newFatal( 'redirectexternal-no-url' );
		}
		$url = filter_var( $url, FILTER_SANITIZE_URL );
		if ( !filter_var( $url, FILTER_VALIDATE_URL ) ) {
			return Status::newFatal( 'redirectexternal-invalid-url', $url );
		}
		return Status::newGood();
	}
}
