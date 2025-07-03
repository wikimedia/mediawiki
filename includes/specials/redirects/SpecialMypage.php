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

namespace MediaWiki\Specials\Redirects;

use MediaWiki\SpecialPage\RedirectSpecialArticle;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserConfig;

/**
 * Redirect to the current user's user page.
 *
 * @ingroup SpecialPage
 */
class SpecialMypage extends RedirectSpecialArticle {

	private TempUserConfig $tempUserConfig;

	public function __construct( TempUserConfig $tempUserConfig ) {
		parent::__construct( 'Mypage' );

		$this->tempUserConfig = $tempUserConfig;
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
			return Title::makeTitle( NS_USER, $this->getUser()->getName() );
		}

		return Title::makeTitle( NS_USER, $this->getUser()->getName() . '/' . $subpage );
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
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialMypage::class, 'SpecialMypage' );
