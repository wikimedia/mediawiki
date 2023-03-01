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
 * @ingroup SpecialPage
 */

use MediaWiki\Title\Title;

/**
 * Special page pointing to current user's Special:Log.
 *
 * @ingroup SpecialPage
 */
class SpecialMylog extends RedirectSpecialPage {
	public function __construct() {
		parent::__construct( 'Mylog' );
		$this->mAllowedRedirectParams = [ 'type', 'subtype', 'page', 'pattern',
			'tagfilter', 'tagInvert', 'offset', 'dir', 'offender',
			'year', 'month', 'day' ];
	}

	/**
	 * @param string|null $subpage
	 * @return Title
	 */
	public function getRedirect( $subpage ) {
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
