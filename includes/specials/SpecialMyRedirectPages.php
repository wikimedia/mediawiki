<?php
/**
 * Special pages that are used to get user independent links pointing to
 * current user's pages (user page, talk page, contributions, etc.).
 * This can let us cache a single copy of some generated content for all
 * users or be linked in wikitext help pages.
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
 * Special page pointing to current user's user page.
 *
 * @ingroup SpecialPage
 */
class SpecialMypage extends RedirectSpecialArticle {
	function __construct() {
		parent::__construct( 'Mypage' );
	}

	function getRedirect( $subpage ) {
		if ( strval( $subpage ) !== '' ) {
			return Title::makeTitle( NS_USER, $this->getUser()->getName() . '/' . $subpage );
		} else {
			return Title::makeTitle( NS_USER, $this->getUser()->getName() );
		}
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
 * Special page pointing to current user's talk page.
 *
 * @ingroup SpecialPage
 */
class SpecialMytalk extends RedirectSpecialArticle {
	function __construct() {
		parent::__construct( 'Mytalk' );
	}

	function getRedirect( $subpage ) {
		if ( strval( $subpage ) !== '' ) {
			return Title::makeTitle( NS_USER_TALK, $this->getUser()->getName() . '/' . $subpage );
		} else {
			return Title::makeTitle( NS_USER_TALK, $this->getUser()->getName() );
		}
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
 * Special page pointing to current user's contributions.
 *
 * @ingroup SpecialPage
 */
class SpecialMycontributions extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'Mycontributions' );
		$this->mAllowedRedirectParams = array( 'limit', 'namespace', 'tagfilter',
			'offset', 'dir', 'year', 'month', 'feed' );
	}

	function getRedirect( $subpage ) {
		return SpecialPage::getTitleFor( 'Contributions', $this->getUser()->getName() );
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
 * Special page pointing to current user's uploaded files.
 *
 * @ingroup SpecialPage
 */
class SpecialMyuploads extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'Myuploads' );
		$this->mAllowedRedirectParams = array( 'limit', 'ilshowall', 'ilsearch' );
	}

	function getRedirect( $subpage ) {
		return SpecialPage::getTitleFor( 'Listfiles', $this->getUser()->getName() );
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
 * Special page pointing to current user's uploaded files (including old versions).
 *
 * @ingroup SpecialPage
 */
class SpecialAllMyUploads extends RedirectSpecialPage {
	function __construct() {
		parent::__construct( 'AllMyUploads' );
		$this->mAllowedRedirectParams = array( 'limit', 'ilsearch' );
	}

	function getRedirect( $subpage ) {
		$this->mAddedRedirectParams['ilshowall'] = 1;

		return SpecialPage::getTitleFor( 'Listfiles', $this->getUser()->getName() );
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
