<?php
/**
 * Redirect from Special:Purge/### to index.php?title=###&action=purge
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
 * Redirect from Special:Purge/### to index.php?title=###&action=purge.
 *
 * Valid usage
 * - [[Special:Purge/12345]]
 *
 * Invalid usage
 * - [[Special:Purge]]
 *
 * @ingroup SpecialPage
 * @since 1.23
 */
class SpecialPurge extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'Purge' );
		$this->mAllowedRedirectParams = array();
	}

	function getRedirect( $subpage ) {
		$parts = explode( '/', $subpage );

		$this->mAddedRedirectParams['action'] = 'purge';
		if ( count( $parts ) === 1 ) {
			$this->mAddedRedirectParams['title'] = $parts[0];
		} else {
			// Wrong number of parameters, redirect to Main Page
			$this->mAddedRedirectParams['title'] = Title::newMainPage();
		}

		return true;
	}
}