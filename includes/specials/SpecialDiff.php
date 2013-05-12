<?php
/**
 * Redirect from Special:Diff/### to index.php?diff=###.
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
 * Redirect from Special:Diff/### to index.php?diff=###.
 *
 * All of the following are valid usages:
 * - [[Special:Diff/12345]] (diff of a revision with the previous one)
 * - [[Special:Diff/12345/prev]] (diff of a revision with the previous one as well)
 * - [[Special:Diff/12345/next]] (diff of a revision with the next one)
 * - [[Special:Diff/12345/98765]] (diff between arbitrary two revisions)
 *
 * @ingroup SpecialPage
 */
class SpecialDiff extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'Diff' );
		$this->mAllowedRedirectParams = array();
	}

	function getRedirect( $subpage ) {
		$parts = explode( '/', $subpage );

		// Try to parse the values given, generating somewhat pretty URLs if possible
		$okay = false;
		if ( count( $parts ) === 1 ) {
			$diff = $this->validateOldid( $parts[0] );
			if ( $diff ) {
				$this->mAddedRedirectParams['diff'] = $diff;
				$okay = true;
			}
		} elseif ( count( $parts ) === 2 ) {
			$oldid = $this->validateOldid( $parts[0] );
			$diff = $this->validateOldidOrDiff( $parts[1] );
			if ( $oldid && $diff ) {
				$this->mAddedRedirectParams['oldid'] = $oldid;
				$this->mAddedRedirectParams['diff'] = $diff;
				$okay = true;
			}
		}

		// Wrong number of parameters or obviously invalid values, bail out
		if ( !$okay ) {
			throw new ErrorPageError( 'nopagetitle', 'nopagetext' );
		}

		return true;
	}

	/**
	 * Check if a parameter (to be passed as ?diff= or ?oldid= URL parameter) is reasonable. Returns a
	 * sanitized value if it is or false if it isn't.
	 *
	 * @param string $value
	 * @return string|int|boolean
	 */
	private function validateOldidOrDiff( $value ) {
		$value = trim( $value );
		if ( $value === 'prev' || $value === 'next' ) {
			return $value;
		}
		return $this->validateOldid( $value );
	}

	/**
	 * Check if a parameter (to be passed as ?oldid= URL parameter) is reasonable. Returns a sanitized
	 * value if it is or false if it isn't.
	 *
	 * @param string $value
	 * @return int|boolean
	 */
	private function validateOldid( $value ) {
		$value = trim( $value );
		if ( intval( $value ) > 0 ) {
			return intval( $value );
		}
		return false;
	}
}
