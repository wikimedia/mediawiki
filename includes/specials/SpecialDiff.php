<?php
/**
 * Redirect from Special:Diff/### to index.php?diff=### and
 * from Special:Diff/###/### to index.php?oldid=###&diff=###.
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
 * Redirect from Special:Diff/### to index.php?diff=### and
 * from Special:Diff/###/### to index.php?oldid=###&diff=###.
 *
 * All of the following are valid usages:
 * - [[Special:Diff/12345]] (diff of a revision with the previous one)
 * - [[Special:Diff/12345/prev]] (diff of a revision with the previous one as well)
 * - [[Special:Diff/12345/next]] (diff of a revision with the next one)
 * - [[Special:Diff/12345/cur]] (diff of a revision with the latest one of that page)
 * - [[Special:Diff/12345/98765]] (diff between arbitrary two revisions)
 *
 * @ingroup SpecialPage
 * @since 1.23
 */
class SpecialDiff extends RedirectSpecialPage {
	public function __construct() {
		parent::__construct( 'Diff' );
		$this->mAllowedRedirectParams = [];
	}

	/**
	 * @param string|null $subpage
	 * @return Title|bool
	 */
	public function getRedirect( $subpage ) {
		$parts = explode( '/', $subpage );

		// Try to parse the values given, generating somewhat pretty URLs if possible
		if ( count( $parts ) === 1 && $parts[0] !== '' ) {
			$this->mAddedRedirectParams['diff'] = $parts[0];
		} elseif ( count( $parts ) === 2 ) {
			$this->mAddedRedirectParams['oldid'] = $parts[0];
			$this->mAddedRedirectParams['diff'] = $parts[1];
		} else {
			// Wrong number of parameters, bail out
			$this->addHelpLink( 'Help:Diff' );
			throw new ErrorPageError( 'nopagetitle', 'nopagetext' );
		}

		return true;
	}
}
